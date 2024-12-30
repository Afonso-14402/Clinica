<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function indext()
    {
        $user = Auth::user();
        return view('index', compact('user'));
    }
    
    public function index()
    {
        $user = Auth::user();
        $role = $user->role->role;
    
        // Verifica o papel do usuário
        switch ($role) {
            case 'Patient':
                return $this->handlePatientDashboard($user);
    
            case 'Doctor':
                return $this->handleDoctorDashboard($user);
    
            default:
                // Se o papel não for reconhecido, redireciona para uma página genérica ou erro
                return redirect()->route('index')->with('error', 'Acesso não autorizado.');
        }
    }
    
    private function handlePatientDashboard($user)
    {
        // Dados específicos para o paciente
        $nextAppointment = Appointment::where('patient_user_id', $user->id)
            ->where('appointment_date_time', '>', Carbon::now())
            ->orderBy('appointment_date_time', 'asc')
            ->first();
    
        $todayAppointmentsCount = Appointment::where('patient_user_id', $user->id)
            ->whereDate('appointment_date_time', Carbon::today())
            ->count();
    
        $plannedAppointments = Appointment::where('patient_user_id', $user->id)->count();
    
        $appointments = Appointment::where('patient_user_id', $user->id)
            ->with(['doctor', 'specialty', 'status'])
            ->orderBy('appointment_date_time', 'desc')
            ->get();
    
        return view('patient.index', compact(
            'user',
            'nextAppointment',
            'todayAppointmentsCount',
            'plannedAppointments',
            'appointments'
        ));
    }
    
    private function handleDoctorDashboard($user)
{
    // Data atual
    $today = Carbon::today();

    // Próxima consulta
    $nextAppointment = Appointment::where('doctor_user_id', $user->id)
        ->where('appointment_date_time', '>', Carbon::now())
        ->orderBy('appointment_date_time', 'asc')
        ->first();

    // Consultas realizadas hoje
    $totalConsultasHoje = Appointment::where('doctor_user_id', $user->id)
        ->whereDate('appointment_date_time', $today)
        ->whereHas('status', function ($query) {
            $query->where('status', 'Realizada');
        })
        ->count();

    // Consultas previstas (agendadas)
    $consultasAgendadas = Appointment::where('doctor_user_id', $user->id)
        ->whereDate('appointment_date_time', $today)
        ->whereHas('status', function ($query) {
            $query->where('status', 'Agendada');
        })
        ->count();

    // Tempo restante para a próxima consulta em horas e minutos
    $tempoRestante = $nextAppointment
        ? Carbon::now()->diff(Carbon::parse($nextAppointment->appointment_date_time))
        : null;

    // Todas as consultas do médico
    $appointments = Appointment::where('doctor_user_id', $user->id)
        ->with(['patient', 'specialty', 'status'])
        ->orderBy('appointment_date_time', 'desc')
        ->get();

    // Retorna a view com os dados necessários
    return view('doctor.index', compact(
        'user',
        'nextAppointment',
        'totalConsultasHoje',
        'consultasAgendadas',
        'tempoRestante',
        'appointments'
    ));
}

    
    
    


    public function getAppointments()
    {
        $user = auth()->user();

        // Todas as consultas do paciente
        $appointments = Appointment::where('patient_user_id', $user->id)
            ->with(['doctor', 'specialty', 'status'])
            ->orderBy('appointment_date_time', 'desc')
            ->get();

        return view('appointments.list', compact('appointments'));
    }


    public function updateAvatar(Request $request)
    {
        // Validação do arquivo de imagem
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,gif|max:800', // Máximo de 800 KB
        ]);
    
       
        $user = auth()->user();
    
        // Exclua o avatar antigo, caso exista
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar); 
        }
    
        // Armazenar o novo avatar
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
    
        // Atribua o caminho do avatar ao usuário e salve no banco de dados
        $user->avatar = $avatarPath;
        $user->save(); 
    
        // Retorne para a página anterior com uma mensagem de sucesso
        return redirect()->back()->with('success', 'Avatar atualizado com sucesso!');
    }
    
    

    

}


