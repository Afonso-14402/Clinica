<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Specialty;
use App\Models\Report;
use App\Models\ActivityLog;
use Illuminate\Http\Request; 



class DashboardController extends Controller
{
    public function index()
    {
        // Dados gerais do dashboard
        $totalUsers = User::count();
        $totalPatients = User::where('role_id', 3)->count(); // role_id=3 para pacientes
        $totalDoctors = User::where('role_id', 2)->count(); // role_id=2 para médicos
        $totalAppointments = Appointment::count();
        $pendingAppointments = Appointment::where('status_id', 1)->count(); // status_id=1 = pendente
        $totalSpecialties = Specialty::count();
        $totalReports = Report::count();

        // Atividades mais recentes
        $activities = ActivityLog::with('user') // Inclui informações do usuário
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        
            $activities = ActivityLog::paginate(5); // Pagina com 5 itens por página
           

        // Retornar para a view com todos os dados
        $user = Auth::user();
        return view('dashboard.index', compact(
            'user', 
            'totalUsers', 
            'totalPatients', 
            'totalDoctors', 
            'totalAppointments', 
            'pendingAppointments', 
            'totalSpecialties', 
            'totalReports', 
            'activities'
            
        ));
    }

    public function fetchActivities(Request $request)
{
    $activities = ActivityLog::with('user')->paginate(5); 
    return response()->json($activities); 
}
    
}
