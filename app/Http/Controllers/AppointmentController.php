<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Specialty;
use App\Models\Status;
use App\Models\UserDoctorAgenda;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function create()
    {
        // Buscar médicos (com especialidades) e pacientes do banco de dados
        $doctors = User::whereHas('role', function ($query) {
            $query->where('role', 'doctor');
        })->get();

        $patients = User::whereHas('role', function ($query) {
            $query->where('role', 'patient');
        })->get();

        $specialties = Specialty::all();
        $statuses = Status::all();

        $user = Auth::user()->load('role');

        return view('appointments.create', compact('doctors', 'patients', 'specialties', 'statuses', 'user'));
    }

    public function store(Request $request)
    {
        try {
            // Validar os dados enviados
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

            // 1. Verificar a agenda do médico
            $doctorSchedule = UserDoctorAgenda::where('doctor_id', $doctorId)
                ->where('day_of_week', $dayOfWeek)
                ->first();

            if (!$doctorSchedule || $appointmentTime < $doctorSchedule->start_time || $appointmentTime > $doctorSchedule->end_time) {
                return redirect()->back()->with('error', 'O horário selecionado não está disponível para este médico.');
            }

            // 2. Verificar se já existe uma consulta no mesmo horário
            $existingAppointment = Appointment::where('doctor_user_id', $doctorId)
                ->where('appointment_date_time', $appointmentDateTime)
                ->exists();

            if ($existingAppointment) {
                return redirect()->back()->with('error', 'Já existe uma consulta marcada para este horário.');
            }

            // 3. Garantir que o horário seja múltiplo de 30 minutos
            if ($appointmentDateTime->minute % 30 !== 0) {
                return redirect()->back()->with('error', 'Os horários de consulta devem ser em intervalos de 30 minutos (ex: 08:00, 08:30).');
            }

            // Adicionar o status como 'Scheduled'
            $validated['status_id'] = Status::where('status', 'Scheduled')->firstOrFail()->id;

            // Criar o agendamento
            Appointment::create($validated);

            // Mensagem de sucesso
            return redirect()->route('appointments.create')->with('success', 'Consulta agendada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocorreu um erro ao criar o agendamento. Tente novamente.');
        }
    }
}
