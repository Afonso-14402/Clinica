<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

   
    
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

            case 'Admin':
                return $this->handleAdminDashboard($user);
            
            default:
                abort(403, 'Role não autorizada ou desconhecida.');
        }
    }

    public function redirectToHome()
    {
        $user = Auth::user();
        $role = $user->role->role;

        switch ($role) {
            case 'Admin':
                return redirect()->route('index'); 
            case 'Doctor':
                return redirect()->route('doctor.index'); 
            case 'Patient':
                return redirect()->route('patient.index');
            default:
                abort(403, 'Role não autorizada ou desconhecida.');
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

    private function handleAdminDashboard($user)
    {

        $user = Auth::user();
        return view('index', compact('user'));
       
    }
    private function handleDoctorDashboard($user)
    {
        // Data atual
        $today = Carbon::today();

        // Próxima consulta (somente com status 1)
        $nextAppointment = Appointment::where('doctor_user_id', $user->id)
            ->where('appointment_date_time', '>', Carbon::now())
            ->where('status_id', 1) // Considera apenas status 1
            ->orderBy('appointment_date_time', 'asc')
            ->first();

        // Consultas previstas para hoje (somente com status 1)
        $consultasAgendadas = Appointment::where('doctor_user_id', $user->id)
            ->whereDate('appointment_date_time', $today)
            ->where('status_id', 1)
            ->count();

        // Tempo restante para a próxima consulta em horas e minutos
        $tempoRestante = $nextAppointment
            ? Carbon::now()->diff(Carbon::parse($nextAppointment->appointment_date_time))
            : null;

        // Todas as consultas do médico com status 1
        $appointments = Appointment::where('doctor_user_id', $user->id)
            ->where('status_id', 1) // Filtro para status 1
            ->with(['patient', 'specialty', 'status'])
            ->orderBy('appointment_date_time', 'desc')
            ->get();

        // Consultas paginadas (somente com status 1)
        $custasdortor = Appointment::where('doctor_user_id', $user->id)
            ->where('status_id', 1) // Filtro para status 1
            ->with(['patient', 'specialty', 'status'])
            ->orderBy('appointment_date_time', 'desc')
            ->paginate(10);

        // Retorna a view com os dados necessários
        return view('doctor.index', compact(
            'user',
            'nextAppointment',
            'consultasAgendadas',
            'tempoRestante',
            'appointments',
            'custasdortor'
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


