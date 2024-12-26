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
    
            // Capturar dados do formulário
            $doctorId = $validated['doctor_user_id'];
            $appointmentDateTime = Carbon::parse($validated['appointment_date_time']);
            $dayOfWeek = $appointmentDateTime->dayOfWeek; // 0 = Domingo, 6 = Sábado
            $appointmentTime = $appointmentDateTime->format('H:i:s');
    
            // Intervalo de 30 minutos
            $startRange = $appointmentDateTime->copy()->subMinutes(30);
            $endRange = $appointmentDateTime->copy()->addMinutes(30);
    
            // Verificar conflitos de horário
            $conflictingAppointment = Appointment::where('doctor_user_id', $doctorId)
                ->whereDate('appointment_date_time', $appointmentDateTime->toDateString())
                ->where(function ($query) use ($startRange, $endRange) {
                    $query->whereBetween('appointment_date_time', [$startRange, $endRange]);
                })->exists();
    
            if ($conflictingAppointment) {
                return redirect()->back()->with('error', 'Já existe uma consulta próxima a este horário. Deve haver um intervalo de 30 minutos.');
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
            $patient = User::find($appointment->patient_user_id);
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
}
