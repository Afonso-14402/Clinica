<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Specialty;
use App\Models\Status;
use App\Models\UserDoctorAgenda;
use App\Models\ActivityLog; // Certifique-se de ter o modelo ActivityLog
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\DoctorReport;
use App\Models\Image;

/**
 * Controlador responsável pela gestão de consultas médicas
 */
class AppointmentController extends Controller
{
    /**
     * Apresenta o formulário de criação de consulta
     * Carrega médicos, pacientes e especialidades disponíveis
     */
    public function create()
    {
        try {
            // Obter médicos ativos
            $doctors = User::whereHas('role', function ($query) {
                $query->where('role', 'doctor');
            })->get();

            // Obter pacientes ativos
            $patients = User::whereHas('role', function ($query) {
                $query->where('role', 'patient');
            })->get();

            $specialties = Specialty::all();
            $statuses = Status::all();
            $user = Auth::user()->load('role');

            // Obter registo de atividades recentes
            $activities = ActivityLog::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            return view('appointments.create', compact('doctors', 'patients', 'specialties', 'statuses', 'user', 'activities'));
        } catch (\Exception $e) {
            \Log::error('Erro ao aceder formulário de agendamento: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao aceder o formulário. Tente novamente.');
        }
    }

    /**
     * Armazena uma nova consulta no sistema
     * Inclui validações de disponibilidade e registo de atividade
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'patient_user_id' => 'required|exists:users,id',
                'doctor_user_id' => 'required|exists:users,id',
                'specialties_id' => 'required|exists:specialties,id',
                'appointment_date_time' => 'required|date_format:Y-m-d H:i',
            ]);

            // Verificar disponibilidade do horário
            $existingAppointment = Appointment::where('doctor_user_id', $request->doctor_user_id)
                ->whereDate('appointment_date_time', Carbon::parse($request->appointment_date_time)->toDateString())
                ->whereTime('appointment_date_time', Carbon::parse($request->appointment_date_time)->format('H:i:00'))
                ->exists();

            if ($existingAppointment) {
                return redirect()->back()
                    ->with('error', 'Este horário já não está disponível. Por favor, escolha outro horário.')
                    ->withInput();
            }

            // Criar a consulta
            $appointment = Appointment::create([
                'patient_user_id' => $request->patient_user_id,
                'doctor_user_id' => $request->doctor_user_id,
                'specialties_id' => $request->specialties_id,
                'status_id' => 1, // Estado inicial (Agendada)
                'appointment_date_time' => $request->appointment_date_time,
            ]);

            // Obter informações para o registo
            $patient = User::find($request->patient_user_id);
            $doctor = User::find($request->doctor_user_id);
            $specialty = Specialty::find($request->specialties_id);

            // Registar atividade
            ActivityLog::create([
                'type' => 'agendamento_consulta',
                'description' => "Consulta agendada: Paciente {$patient->name} com Dr(a). {$doctor->name} " .
                               "para {$appointment->appointment_date_time} na especialidade {$specialty->name}",
                'user_id' => Auth::id()
            ]);

            return redirect()->back()->with('success', 'Consulta agendada com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao criar agendamento: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ocorreu um erro ao criar o agendamento. Tente novamente.')
                ->withInput();
        }
    }

    /**
     * Obtém todas as consultas para apresentação no calendário
     */
    public function getEvents(Request $request)
    {
        $appointments = Appointment::with(['doctor', 'patient', 'specialty'])
            ->get()
            ->map(function ($appointment) {
                // Garantir que a data da consulta é uma instância Carbon
                $appointmentDate = \Carbon\Carbon::parse($appointment->appointment_date_time);

                return [
                    'id' => $appointment->id,
                    'title' => $appointment->patient->name . ' - ' . $appointment->specialty->name,
                    'start' => $appointment->appointment_date_time,
                    'extendedProps' => [
                        'patient' => $appointment->patient->name,
                        'doctor' => $appointment->doctor->name,
                        'type' => $appointment->specialty->name,
                        'appointment_time' => $appointmentDate->format('H:i'),
                    ],
                ];
            });

        return response()->json($appointments);
    }

    /**
     * Processa o pedido de agendamento de consulta
     * Inclui validações de horário e médico de família
     */
    public function requestAppointment(Request $request)
    {
        try {
            // Validar os dados de entrada
            $validated = $request->validate([
                'patient_user_id' => 'required|exists:users,id',
                'appointment_day' => 'required|date',
                'appointment_time' => [
                    'required',
                    'date_format:H:i',
                    function ($attribute, $value, $fail) use ($request) {
                        // Combinar a data e o horário para validação
                        $dateTime = Carbon::createFromFormat('Y-m-d H:i', $request->appointment_day . ' ' . $value);
                        if ($dateTime->isPast()) {
                            $fail('Não é possível selecionar um horário no passado.');
                        }
                    },
                ],
            ]);

            // Buscar o paciente
            $patientId = $validated['patient_user_id'];
            $patient = User::find($patientId);

            if (!$patient) {
                return redirect()->back()->with('error', 'Paciente não encontrado.');
            }

            // Buscar o registro do médico de família associado ao paciente
            $familyDoctorRecord = $patient->familyDoctor;

            if (!$familyDoctorRecord || !$familyDoctorRecord->doctor) {
                return redirect()->back()->with('error', 'Nenhum médico de família está associado a este paciente.');
            }

            // Obter o médico associado
            $doctor = $familyDoctorRecord->doctor;
            $doctorId = $doctor->id;

            // Combinar o dia e o horário em um único campo datetime
            $appointmentDateTime = Carbon::createFromFormat(
                'Y-m-d H:i',
                $validated['appointment_day'] . ' ' . $validated['appointment_time']
            );

            // Verificar conflitos de horário
            $conflictingAppointment = Appointment::where('doctor_user_id', $doctorId)
                ->where('appointment_date_time', $appointmentDateTime)
                ->first();

            if ($conflictingAppointment) {
                return redirect()->back()->with('error', 'Esse horário já está reservado para outro paciente.');
            }

            // Definir os valores adicionais
            $validated['appointment_date_time'] = $appointmentDateTime;
            $validated['specialties_id'] = 4; // Exemplo: Medicina Geral e Familiar
            $validated['doctor_user_id'] = $doctorId;
            $validated['status_id'] = Status::where('status', 'Pendente')->firstOrFail()->id;

            // Criar o agendamento
            $appointment = Appointment::create($validated);

            // Criar log de atividade
            ActivityLog::create([
                'type' => 'appointment',
                'description' => 'Consulta marcada: Paciente ' . $patient->name .
                                 ' com o médico ' . $doctor->name .
                                 ' para ' . $appointmentDateTime->format('d/m/Y H:i') .
                                 ' na especialidade Medicina Geral e Familiar.',
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('patient.index')->with('success', 'Consulta agendada com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->with('error', 'Erro de validação: Verifique os campos obrigatórios.');
        } catch (\Exception $e) {
            \Log::error('Erro ao agendar consulta', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Ocorreu um erro inesperado: ' . $e->getMessage());
        }
    }

    /**
     * Apresenta a lista de consultas pendentes para aprovação
     */
    public function showPendingAppointments()
    {
        try {
            // Obter todas as consultas com estado Pendente
            $appointments = Appointment::where('status_id', 4)->get();
            $user = Auth::user();

            return view('appointments.pending', compact('appointments', 'user'));
        } catch (\Exception $e) {
            \Log::error('Erro ao aceder consultas pendentes: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao aceder as consultas pendentes.');
        }
    }

    /**
     * Atualiza o estado de uma consulta
     * Regista a alteração no histórico de atividades
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $oldStatus = $appointment->status;
            
            // Validar o estado recebido
            $status = $request->input('status');
            
            if (!in_array($status, [1, 3])) {
                return back()->withErrors(['error' => 'Estado inválido!']);
            }
            
            // Atualizar o estado
            $appointment->update(['status_id' => $status]);

            // Obter o novo estado para o registo
            $newStatus = Status::find($status)->status;
            
            // Registar alteração de estado
            ActivityLog::create([
                'type' => 'alteracao_estado_consulta',
                'description' => "Estado da consulta do paciente {$appointment->patient->name} alterado de '{$oldStatus}' para '{$newStatus}'",
                'user_id' => Auth::id()
            ]);
            
            $message = $status == 1 ? 'Consulta aprovada com sucesso!' : 'Consulta rejeitada com sucesso!';
            return back()->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar estado da consulta: ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao atualizar o estado da consulta.');
        }
    }

    /**
     * Obtém a agenda de um médico específico
     */
    public function getSchedule($id)
    {
        try {
            $schedules = UserDoctorAgenda::where('doctor_id', $id)->get();
            return response()->json($schedules);
        } catch (\Exception $e) {
            \Log::error('Erro ao obter agenda: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao obter agenda'], 500);
        }
    }

    /**
     * Aprova uma consulta pendente
     * Regista a aprovação no histórico de atividades
     */
    public function approve(Appointment $appointment)
    {
        try {
            $oldStatus = $appointment->status->status;
            $appointment->update(['status_id' => 1]); // 1 = Aprovado

            // Registar aprovação
            ActivityLog::create([
                'type' => 'aprovacao_consulta',
                'description' => "Consulta do paciente {$appointment->patient->name} foi aprovada",
                'user_id' => Auth::id()
            ]);

            return redirect()->back()->with('success', 'Consulta aprovada com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao aprovar consulta: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao aprovar a consulta.');
        }
    }

    /**
     * Obtém a lista de médicos ativos no sistema
     */
    public function getDoctorsList()
    {
        try {
            $doctors = User::whereHas('role', function ($query) {
                $query->where('role', 'doctor');
            })->where('status', 1)->get(['id', 'name']);

            return response()->json($doctors);
        } catch (\Exception $e) {
            \Log::error('Erro ao obter lista de médicos: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao obter lista de médicos'], 500);
        }
    }

    /**
     * Reagenda uma consulta existente
     * Inclui validações de disponibilidade e horário do médico
     */
    public function reschedule(Request $request, Appointment $appointment)
    {
        // Validação dos dados do formulário
        $request->validate([
            'new_date' => 'required|date',
            'new_time' => 'required',
            'new_doctor_id' => 'required|exists:users,id'
        ]);

        $newDateTime = $request->new_date . ' ' . $request->new_time;

        // Verificar disponibilidade do novo médico
        $newDoctorId = $request->new_doctor_id;
        $appointmentDateTime = Carbon::parse($newDateTime);
        $dayOfWeek = $appointmentDateTime->dayOfWeek;

        // Verificar agenda do novo médico
        $doctorSchedule = UserDoctorAgenda::where('doctor_id', $newDoctorId)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$doctorSchedule) {
            return redirect()->back()->with('error', 'O médico selecionado não atende neste dia da semana.');
        }

        // Verificar se o horário está dentro do expediente do médico
        $appointmentTime = $appointmentDateTime->format('H:i:s');
        if ($appointmentTime < $doctorSchedule->start_time || $appointmentTime > $doctorSchedule->end_time) {
            return redirect()->back()->with('error', 'Horário fora do expediente do médico.');
        }

        // Verificar conflitos de horário
        $conflictingAppointment = Appointment::where('doctor_user_id', $newDoctorId)
            ->where('id', '!=', $appointment->id)
            ->whereDate('appointment_date_time', $appointmentDateTime->toDateString())
            ->whereTime('appointment_date_time', $appointmentTime)
            ->exists();

        if ($conflictingAppointment) {
            return redirect()->back()->with('error', 'Já existe uma consulta marcada para este horário.');
        }

        // Atualizar a consulta
        $appointment->update([
            'appointment_date_time' => $newDateTime,
            'doctor_user_id' => $newDoctorId
        ]);

        // Registar no histórico de atividades
        ActivityLog::create([
            'type' => 'reagendamento_consulta',
            'description' => sprintf(
                'Consulta reagendada: ID #%d - Nova data/hora: %s com Dr(a). %s',
                $appointment->id,
                $appointmentDateTime->format('d/m/Y H:i'),
                User::find($newDoctorId)->name
            ),
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Consulta reagendada com sucesso!');
    }

    /**
     * Guarda o relatório médico de uma consulta
     * Inclui processamento de imagens e atualização do estado da consulta
     */
    public function salvarRelatorio(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        
        // Validação dos dados do formulário
        $validatedData = $request->validate([
            'sintomas' => 'required',
            'diagnostico' => 'required',
            'tratamento' => 'required',
            'observacoes' => 'required',
            'exam_images.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120' // 5MB máx
        ]);

        // Array para armazenar os caminhos das imagens
        $imagePaths = [];

        // Processar as imagens se existirem
        if ($request->hasFile('exam_images')) {
            foreach ($request->file('exam_images') as $image) {
                // Gerar nome único para o ficheiro
                $fileName = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                
                // Guardar ficheiro no armazenamento
                $path = $image->storeAs('exam_images', $fileName, 'public');
                
                // Adicionar caminho ao array
                $imagePaths[] = $path;
            }
        }

        // Criar o relatório com as imagens
        $report = new DoctorReport([
            'content' => json_encode([
                'sintomas' => $request->sintomas,
                'diagnostico' => $request->diagnostico,
                'tratamento' => $request->tratamento,
                'observacoes' => $request->observacoes
            ]),
            'doctor_report_user_id' => auth()->id(),
            'appointment_id' => $id
        ]);
        
        // Guardar os caminhos das imagens como JSON
        $report->exam_images = !empty($imagePaths) ? json_encode($imagePaths) : null;
        
        $report->save();

        // Atualizar o estado da consulta para "Concluída"
        $appointment->status_id = 2;
        $appointment->save();

        return redirect()->route('appointments.index')
            ->with('success', 'Relatório guardado com sucesso!');
    }
}
