<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Specialty;
use App\Models\Status;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador responsável pela gestão do histórico de consultas
 */
class AppointmentHistoryController extends Controller
{
    /**
     * Apresenta o histórico de consultas com opções de filtragem
     * Inclui filtros por período, médico, especialidade e estado
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Consulta base para obter consultas do paciente
        $query = Appointment::with(['doctor', 'specialty', 'status']);

        // Filtro de visualização (como médico ou paciente)
        switch ($request->view_as) {
            case 'doctor':
                if ($user->role_id === 2) { // Se for médico
                    $query->where('doctor_user_id', $user->id);
                }
                break;
            case 'patient':
                $query->where('patient_user_id', $user->id);
                break;
            default:
                // Mostrar todas as consultas relacionadas ao usuário
                $query->where(function($q) use ($user) {
                    $q->where('patient_user_id', $user->id)
                      ->orWhere(function($q) use ($user) {
                          if ($user->role_id === 2) { // Se for médico
                              $q->where('doctor_user_id', $user->id);
                          }
                      });
                });
        }

        // Aplicar filtro por período
        if ($request->date_filter) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('appointment_date_time', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('appointment_date_time', [
                        Carbon::now()->startOfWeek(), 
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'month':
                    $query->whereMonth('appointment_date_time', Carbon::now()->month)
                          ->whereYear('appointment_date_time', Carbon::now()->year);
                    break;
                case 'custom':
                    if ($request->date_start) {
                        $query->whereDate('appointment_date_time', '>=', $request->date_start);
                    }
                    if ($request->date_end) {
                        $query->whereDate('appointment_date_time', '<=', $request->date_end);
                    }
                    break;
            }
        }

        // Aplicar filtro por médico
        if ($request->doctor_id) {
            $query->where('doctor_user_id', $request->doctor_id);
        }

        // Aplicar filtro por especialidade
        if ($request->specialty_id) {
            $query->where('specialties_id', $request->specialty_id);
        }

        // Aplicar filtro por estado
        if ($request->status_id) {
            $query->where('status_id', $request->status_id);
        }

        // Aplicar filtro por período (passado/futuro)
        if ($request->time_period) {
            if ($request->time_period === 'past') {
                $query->where('appointment_date_time', '<', Carbon::now());
            } elseif ($request->time_period === 'future') {
                $query->where('appointment_date_time', '>', Carbon::now());
            }
        }

        // Ordenar por data (mais recente primeiro)
        $query->orderBy('appointment_date_time', 'desc');

        // Obter dados para os filtros
        $doctors = User::whereHas('doctorAppointments', function($q) use ($user) {
            $q->where('patient_user_id', $user->id);
        })->get();
        
        $specialties = Specialty::whereHas('appointments', function($q) use ($user) {
            $q->where('patient_user_id', $user->id);
        })->get();
        
        $statuses = Status::all();

        // Paginar resultados
        $appointments = $query->paginate(10);

        return view('patient.history', compact(
            'appointments',
            'doctors',
            'specialties',
            'statuses',
            'user',
            'request'
        ));
    }
} 