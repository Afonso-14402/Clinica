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

class AppointmentController extends Controller
{
    public function create()
    {
        try {
            // Buscar médicos (com especialidades) e pacientes
            $doctors = User::whereHas('role', function ($query) {
                $query->where('role', 'doctor');
            })->get();

            $patients = User::whereHas('role', function ($query) {
                $query->where('role', 'patient');
            })->get();

            $specialties = Specialty::all();
            $statuses = Status::all();

            $user = Auth::user()->load('role');

            // Atividades mais recentes
            $activities = ActivityLog::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            return view('appointments.create', compact('doctors', 'patients', 'specialties', 'statuses', 'user', 'activities'));
        } catch (\Exception $e) {
            \Log::error('Erro ao acessar formulário de agendamento: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao acessar o formulário. Tente novamente.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'patient_user_id' => 'required|exists:users,id',
                'doctor_user_id' => 'required|exists:users,id',
                'specialties_id' => 'required|exists:specialties,id',
                'appointment_date_time' => 'required|date_format:Y-m-d H:i',
            ]);

            // Verificar se o horário ainda está disponível
            $existingAppointment = Appointment::where('doctor_user_id', $request->doctor_user_id)
                ->whereDate('appointment_date_time', Carbon::parse($request->appointment_date_time)->toDateString())
                ->whereTime('appointment_date_time', Carbon::parse($request->appointment_date_time)->format('H:i:00'))
                ->exists();

            if ($existingAppointment) {
                return redirect()->back()
                    ->with('error', 'Este horário já não está mais disponível. Por favor, escolha outro horário.')
                    ->withInput();
            }

            // Criar a consulta
            $appointment = Appointment::create([
                'patient_user_id' => $request->patient_user_id,
                'doctor_user_id' => $request->doctor_user_id,
                'specialties_id' => $request->specialties_id,
                'status_id' => 1, // Status inicial (Scheduled)
                'appointment_date_time' => $request->appointment_date_time,
            ]);

            // Buscar informações para o log
            $patient = User::find($request->patient_user_id);
            $doctor = User::find($request->doctor_user_id);
            $specialty = Specialty::find($request->specialties_id);

            // Log de agendamento
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

    public function getEvents(Request $request)
    {
        $appointments = Appointment::with(['doctor', 'patient', 'specialty'])
            ->get()
            ->map(function ($appointment) {
                // Ensure the appointment_date_time is a Carbon instance
                $appointmentDate = \Carbon\Carbon::parse($appointment->appointment_date_time);

                return [
                    'id' => $appointment->id,
                    'title' => $appointment->patient->name . ' - ' . $appointment->specialty->name,
                    'start' => $appointment->appointment_date_time,
                    'extendedProps' => [
                        'patient' => $appointment->patient->name,
                        'doctor' => $appointment->doctor->name,
                        'type' => $appointment->specialty->name,
                        'appointment_time' => $appointmentDate->format('H:i'), // Format the Carbon instance
                    ],
                ];
            });

        return response()->json($appointments);
    }

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
                        // Combinar a data e o horário para validar
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
            // Tratamento de erros de validação
            return redirect()->back()
                ->withErrors($e->errors())
                ->with('error', 'Erro de validação: Verifique os campos obrigatórios.');
        } catch (\Exception $e) {
            // Tratamento de outros erros
            \Log::error('Erro ao agendar consulta', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Ocorreu um erro inesperado: ' . $e->getMessage());
        }
    }

    public function showPendingAppointments()
    {
        try {
            // Buscar todos os pedidos com status Pendente
            $appointments = Appointment::where('status_id', 4)->get();
            $user = Auth::user();

            return view('appointments.pending', compact('appointments', 'user'));
        } catch (\Exception $e) {
            \Log::error('Erro ao acessar consultas pendentes: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao acessar as consultas pendentes.');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $oldStatus = $appointment->status;
            
            // Validar o status recebido
            $status = $request->input('status');
            
            if (!in_array($status, [1, 3])) {
                return back()->withErrors(['error' => 'Status inválido!']);
            }
            
            // Atualizar o status
            $appointment->update(['status_id' => $status]);

            // Buscar o novo status para o log
            $newStatus = Status::find($status)->status;
            
            // Log de alteração de status
            ActivityLog::create([
                'type' => 'alteracao_status_consulta',
                'description' => "Status da consulta do paciente {$appointment->patient->name} alterado de '{$oldStatus}' para '{$newStatus}'",
                'user_id' => Auth::id()
            ]);
            
            $message = $status == 1 ? 'Consulta aprovada afonso com sucesso!' : 'Consulta rejeitada com sucesso!';
            return back()->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar status da consulta: ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao atualizar o status da consulta.');
        }
    }

    
    
    public function getSchedule($id)
    {
        try {
            $schedules = UserDoctorAgenda::where('doctor_id', $id)->get();
            return response()->json($schedules);
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar agenda: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar agenda'], 500);
        }
    }

    public function approve(Appointment $appointment)
    {
        try {
            $oldStatus = $appointment->status->status;
            $appointment->update(['status_id' => 1]); // 1 = Aprovado

            // Log de aprovação
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

    public function getDoctorsList()
    {
        try {
            $doctors = User::whereHas('role', function ($query) {
                $query->where('role', 'doctor');
            })->where('status', 1)->get(['id', 'name']);

            return response()->json($doctors);
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar lista de médicos: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar lista de médicos'], 500);
        }
    }

    public function reschedule(Request $request, Appointment $appointment)
    {
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

        $appointment->update([
            'appointment_date_time' => $newDateTime,
            'doctor_user_id' => $newDoctorId
        ]);

        // Registrar no log de atividades
        ActivityLog::create([
            'type' => 'appointment_reschedule',
            'description' => sprintf(
                'Consulta reagendada: ID #%d - Nova data/hora: %s com Dr(a). %s',$appointment->id,
                $appointmentDateTime->format('d/m/Y H:i'),
                User::find($newDoctorId)->name
            ),
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Consulta reagendada com sucesso!');
    }
}
