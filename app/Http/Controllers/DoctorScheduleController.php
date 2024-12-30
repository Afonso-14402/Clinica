<?php 

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDoctorAgenda;
use Illuminate\Http\Request;

class DoctorScheduleController extends Controller
{
    // Método para listar horários do médico
    public function index($doctorId)
    {
        try {
            // Verificar se o médico existe
            $doctor = User::where('id', $doctorId)->whereHas('role', function ($query) {
                $query->where('role', 'Doctor');
            })->first();

            if (!$doctor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Médico não encontrado.',
                ], 404);
            }

            // Buscar os horários do médico
            $schedules = UserDoctorAgenda::where('doctor_id', $doctorId)->get();

            return response()->json([
                'success' => true,
                'schedules' => $schedules,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar horários: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Método para salvar ou atualizar horários do médico
    public function store(Request $request, $doctorId)
    {
        try {
            $doctor = User::where('id', $doctorId)->whereHas('role', function ($query) {
                $query->where('role', 'Doctor');
            })->first();
    
            if (!$doctor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Médico não encontrado.'
                ], 404);
            }
    
            // Deleta os horários existentes antes de salvar os novos
            UserDoctorAgenda::where('doctor_id', $doctorId)->delete();
    
            // Salva os novos horários
            foreach ($request->input('days', []) as $day => $times) {
                if (!empty($times['start_time']) && !empty($times['end_time'])) {
                    UserDoctorAgenda::create([
                        'doctor_id' => $doctorId,
                        'day_of_week' => $day,
                        'start_time' => $times['start_time'],
                        'end_time' => $times['end_time'],
                    ]);
                }
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Horários salvos com sucesso.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar horários: ' . $e->getMessage(),
            ], 500);
        }
        
    }
        
}
