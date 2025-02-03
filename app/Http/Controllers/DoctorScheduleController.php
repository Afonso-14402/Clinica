<?php 

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDoctorAgenda;
use Illuminate\Http\Request;

/**
 * Controlador responsável pela gestão dos horários de atendimento dos médicos
 */
class DoctorScheduleController extends Controller
{
    /**
     * Lista os horários de atendimento de um médico específico
     * 
     * @param int $doctorId Identificador do médico
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($doctorId)
    {
        try {
            // Verificar se o médico existe e tem função adequada
            $doctor = User::where('id', $doctorId)->whereHas('role', function ($query) {
                $query->where('role', 'Doctor');
            })->first();

            if (!$doctor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Médico não encontrado.',
                ], 404);
            }

            // Obter os horários do médico
            $schedules = UserDoctorAgenda::where('doctor_id', $doctorId)->get();

            return response()->json([
                'success' => true,
                'schedules' => $schedules,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter horários: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Guarda ou atualiza os horários de atendimento de um médico
     * 
     * @param Request $request
     * @param int $doctorId Identificador do médico
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $doctorId)
    {
        try {
            // Verificar se o médico existe e tem função adequada
            $doctor = User::where('id', $doctorId)->whereHas('role', function ($query) {
                $query->where('role', 'Doctor');
            })->first();
    
            if (!$doctor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Médico não encontrado.'
                ], 404);
            }
    
            // Eliminar horários existentes antes de guardar os novos
            UserDoctorAgenda::where('doctor_id', $doctorId)->delete();
    
            // Guardar os novos horários
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
                'message' => 'Horários guardados com sucesso.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao guardar horários: ' . $e->getMessage(),
            ], 500);
        }
    }
}
