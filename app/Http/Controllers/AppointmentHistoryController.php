<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Specialty;
use App\Models\Status;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AppointmentHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Query base
        $query = Appointment::where('patient_user_id', $user->id)
            ->with(['doctor', 'specialty', 'status']);

        // Filtro por período
        if ($request->date_filter) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('appointment_date_time', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('appointment_date_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
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

        // Filtro por médico
        if ($request->doctor_id) {
            $query->where('doctor_user_id', $request->doctor_id);
        }

        // Filtro por especialidade
        if ($request->specialty_id) {
            $query->where('specialties_id', $request->specialty_id);
        }

        // Filtro por status
        if ($request->status_id) {
            $query->where('status_id', $request->status_id);
        }

        // Filtro por período (passado/futuro)
        if ($request->time_period) {
            if ($request->time_period === 'past') {
                $query->where('appointment_date_time', '<', Carbon::now());
            } elseif ($request->time_period === 'future') {
                $query->where('appointment_date_time', '>', Carbon::now());
            }
        }

        // Ordenação padrão por data (mais recente primeiro)
        $query->orderBy('appointment_date_time', 'desc');

        // Dados para os filtros
        $doctors = User::whereHas('doctorAppointments', function($q) use ($user) {
            $q->where('patient_user_id', $user->id);
        })->get();
        
        $specialties = Specialty::whereHas('appointments', function($q) use ($user) {
            $q->where('patient_user_id', $user->id);
        })->get();
        
        $statuses = Status::all();

        // Paginação
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