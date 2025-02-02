<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
                return $this->handleAdminDashboard($user);
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
        try {
            // Dados específicos para o paciente
            $nextAppointment = Appointment::where('patient_user_id', $user->id)
                ->where('appointment_date_time', '>', Carbon::now())
                ->orderBy('appointment_date_time', 'asc')
                ->first();
        
            $todayAppointmentsCount = Appointment::where('patient_user_id', $user->id)
                ->whereDate('appointment_date_time', Carbon::today())
                ->count();
        
            $plannedAppointments = Appointment::where('patient_user_id', $user->id)->count();
        
            // Query base para consultas
            $query = Appointment::where('patient_user_id', $user->id)
                ->with(['doctor', 'specialty', 'status']);

            // Aplicar filtro de data se solicitado
            $filterDate = request('filter_date');
            if ($filterDate === 'today') {
                $query->whereDate('appointment_date_time', Carbon::today());
            } else {
                // Se não houver filtro de data, mostrar apenas consultas futuras
                $query->where('appointment_date_time', '>', Carbon::now());
            }

            // Aplicar filtro de médico se solicitado
            $filterDoctor = request('filter_doctor');
            if ($filterDoctor) {
                $query->where('doctor_user_id', $filterDoctor);
            }

            // Obter apenas os médicos que têm consultas com este paciente
            $doctors = User::whereHas('doctorAppointments', function($q) use ($user) {
                $q->where('patient_user_id', $user->id);
            })->get();

            // Executar a query com os filtros aplicados
            $appointments = $query->orderBy('appointment_date_time', 'asc')->paginate(10);
        
            return view('patient.index', compact(
                'user',
                'nextAppointment',
                'todayAppointmentsCount',
                'plannedAppointments',
                'appointments',
                'doctors'
            ));
        } catch (\Exception $e) {
            \Log::error('Erro ao carregar dashboard do paciente: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao carregar o dashboard. Tente novamente.');
        }
    }

    private function handleAdminDashboard($user)
    {
        try {
            // Estatísticas Gerais
            $totalMedicos = User::where('role_id', 2)->where('status', 1)->count();
            $totalPacientes = User::where('role_id', 3)->where('status', 1)->count();
            $totalConsultasRealizadas = Appointment::where('status_id', 2)->count();
            $totalEspecialidades = Specialty::count();

            // Consultas Recentes
            $consultasRecentes = Appointment::with(['patient', 'doctor', 'specialty'])
                ->orderBy('appointment_date_time', 'desc')
                ->take(10)
                ->get()
                ->map(function ($consulta) {
                    return [
                        'paciente' => $consulta->patient->name ?? 'N/A',
                        'medico' => $consulta->doctor->name ?? 'N/A',
                        'data' => Carbon::parse($consulta->appointment_date_time)->format('d/m/Y H:i'),
                        'especialidade' => $consulta->specialty->name ?? 'N/A',
                        'status' => $this->getStatusLabel($consulta->status_id)
                    ];
                });

            // Estatísticas Adicionais
            $consultasHoje = Appointment::whereDate('appointment_date_time', Carbon::today())->count();
            $consultasPendentes = Appointment::where('status_id', 4)->count();
            $consultasCanceladas = Appointment::where('status_id', 3)->count();

            // Log de Atividades
            $activityLogs = DB::table('activity_log')
                ->leftJoin('users', 'activity_log.user_id', '=', 'users.id')
                ->select('activity_log.*', 'users.name as user_name')
                ->orderBy('activity_log.created_at', 'desc')
                ->paginate(15);

            return view('admin.dashboard', compact(
                'user',
                'totalMedicos',
                'totalPacientes',
                'totalConsultasRealizadas',
                'totalEspecialidades',
                'consultasRecentes',
                'consultasHoje',
                'consultasPendentes',
                'consultasCanceladas',
                'activityLogs'
            ));

        } catch (\Exception $e) {
            \Log::error('Erro ao carregar dashboard do admin: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao carregar o dashboard. Tente novamente.');
        }
    }

    private function getStatusLabel($statusId)
    {
        $status = [
            1 => ['label' => 'Agendada', 'class' => 'badge-primary'],
            2 => ['label' => 'Concluída', 'class' => 'badge-success'],
            3 => ['label' => 'Cancelada', 'class' => 'badge-danger'],
            4 => ['label' => 'Pendente', 'class' => 'badge-warning']
        ];

        return $status[$statusId] ?? ['label' => 'Desconhecido', 'class' => 'badge-secondary'];
    }

    private function handleDoctorDashboard($user)
    {
        try {
            // Data atual
            $today = Carbon::today();

            // Próxima consulta (somente com status 1)
            $nextAppointment = Appointment::where('doctor_user_id', $user->id)
                ->where('appointment_date_time', '>', Carbon::now())
                ->where('status_id', 1)
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
                ->where('status_id', 1)
                ->with(['patient', 'specialty', 'status'])
                ->orderBy('appointment_date_time', 'desc')
                ->get();

            // Consultas paginadas (somente com status 1)
            $custasdortor = Appointment::where('doctor_user_id', $user->id)
                ->where('status_id', 1)
                ->with(['patient', 'specialty', 'status'])
                ->orderBy('appointment_date_time', 'desc')
                ->paginate(10);

            return view('doctor.index', compact(
                'user',
                'nextAppointment',
                'consultasAgendadas',
                'tempoRestante',
                'appointments',
                'custasdortor'
            ));
        } catch (\Exception $e) {
            \Log::error('Erro ao carregar dashboard do médico: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao carregar o dashboard. Tente novamente.');
        }
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
        try {
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

            // Registrar log de atividade
            ActivityLog::create([
                'type' => 'avatar_update',
                'description' => "Usuário {$user->name} atualizou sua foto de perfil",
                'user_id' => $user->id
            ]);
        
            return redirect()->back()->with('success', 'Avatar atualizado com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar avatar: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao atualizar o avatar. Tente novamente.');
        }
    }
    
    

    

    

}


