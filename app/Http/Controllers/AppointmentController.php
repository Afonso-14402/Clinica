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
    }

    public function store(Request $request)
{
    try {
        // Validar os dados
        $validated = $request->validate([
            'patient_user_id' => 'required|exists:users,id',
            'doctor_user_id' => 'required|exists:users,id',
            'specialties_id' => 'required|exists:specialties,id',
            'appointment_date_time' => 'required|date|after_or_equal:now',
        ]);

        // Verificar se o paciente está ativo
        $patient = User::find($validated['patient_user_id']);
        if ($patient->status != 1) { // Considerando 1 como status ativo
            return redirect()->back()->with('error', 'O paciente deve estar ativo para realizar o agendamento.');
        }

        $doctor = User::find($validated['doctor_user_id']);
        if ($doctor->status != 1) { // Considerando 1 como status ativo
            return redirect()->back()->with('error', 'O medico deve estar ativo para realizar o agendamento.');
        }

        // Capturar dados do formulário
        $doctorId = $validated['doctor_user_id'];
        $appointmentDateTime = Carbon::parse($validated['appointment_date_time']);
        $dayOfWeek = $appointmentDateTime->dayOfWeek; // 0 = Domingo, 6 = Sábado
        $appointmentTime = $appointmentDateTime->format('H:i:s');

        // Intervalo de 30 minutos
        $startRange = $appointmentDateTime->copy()->subMinutes(59);
        $endRange = $appointmentDateTime->copy()->addMinutes(59);

        // Verificar conflitos de horário
        $conflictingAppointment = Appointment::where('doctor_user_id', $doctorId)
            ->whereDate('appointment_date_time', $appointmentDateTime->toDateString())
            ->where(function ($query) use ($startRange, $endRange) {
                $query->whereBetween('appointment_date_time', [$startRange, $endRange]);
            })->exists();

        if ($conflictingAppointment) {
            return redirect()->back()->with('error', 'Já existe uma consulta próxima a este horário. Deve haver um intervalo de 1 hora.');
        }

        // Verificar a agenda do médico
        $doctorSchedule = UserDoctorAgenda::where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$doctorSchedule || $appointmentTime < $doctorSchedule->start_time || $appointmentTime > $doctorSchedule->end_time) {
            return redirect()->back()->with('error', 'O horário selecionado não está disponível na agenda do médico.');
        }

        // Adicionar o status como 'Scheduled'
        $validated['status_id'] = Status::where('status', 'Scheduled')->firstOrFail()->id;

        // Criar o agendamento
        $appointment = Appointment::create($validated);

        // Recuperar o paciente e o médico para a descrição do log
        $doctor = User::find($appointment->doctor_user_id);

        // Criar a entrada no log de atividades
        ActivityLog::create([
            'type' => 'appointment',
            'description' => 'Consulta marcada: Paciente ' . $patient->name .
                             ' com o médico ' . $doctor->name .
                             ' para ' . $appointmentDateTime->format('d/m/Y H:i'),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('appointments.create')->with('success', 'Consulta agendada com sucesso!');
    } catch (\Exception $e) {
        // Logar o erro para debug
        \Log::error('Erro ao agendar consulta', ['error' => $e->getMessage()]);
        return redirect()->back()->with('error', 'Ocorreu um erro ao criar o agendamento. Tente novamente.');
    }
}

    public function getEvents(Request $request)
    {
        $appointments = Appointment::with(['doctor', 'patient', 'specialty'])
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->patient->name . ' - ' . $appointment->specialty->name,
                    'start' => $appointment->appointment_date_time,
                    'backgroundColor' => '#00C851', // Customize as needed
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
                'appointment_date_time' => 'required|date|after_or_equal:now',
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
    
            // Definir os valores adicionais
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
                                 ' para ' . Carbon::parse($validated['appointment_date_time'])->format('d/m/Y H:i') .
                                 ' na especialidade Medicina Geral e Familiar.',
                'user_id' => Auth::id(),
            ]);
    
            return redirect()->route('patient.index')->with('success', 'Consulta agendada com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tratamento de erros de validação
            return redirect()->back()->withErrors($e->errors())->with('error', 'Erro de validação: Verifique os campos obrigatórios.');
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
        // Buscar todos os pedidos com status Pendente
        $appointments = Appointment::where('status_id', 4)->get();
        $user = Auth::user();
        return view('appointments.pending', compact('appointments','user'   ));
    }

    public function updateStatus($id)
    {
        $appointment = Appointment::findOrFail($id);
    
        // Atualizar status para "1" (Aprovado)
        $appointment->update(['status_id' => 1]);
    
        return redirect()->back()->with('success', 'Status atualizado para Aprovado!');
    }
    

 

    public function getDoctorAppointments(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required|date',
        ]);
    
        $appointments = Appointment::with(['patient', 'specialty']) // Adicione relacionamentos, se necessário
            ->where('doctor_user_id', $validated['doctor_id'])
            ->whereDate('appointment_date_time', $validated['date'])
            ->get();
    
        return response()->json($appointments);
    }
    
    

    
    public function getSchedule($id)
    {
        $schedules = UserDoctorAgenda::where('doctor_id', $id)->get();
        return response()->json($schedules);
    }
}
