<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserDoctorAgenda;
use App\Models\Appointment;
use Carbon\Carbon;

class DoctorAgendaController extends Controller
{
    public function getAvailableTimes(Request $request)
    {
        try {
            $doctorId = $request->doctor_id;
            $date = $request->day;

            // Converter a data para obter o dia da semana (0 = Domingo, 1 = Segunda, etc)
            $dayOfWeek = Carbon::parse($date)->dayOfWeek;

            // Buscar a agenda do médico para este dia da semana
            $agenda = UserDoctorAgenda::where('doctor_id', $doctorId)
                ->where('day_of_week', $dayOfWeek)
                ->first();

            if (!$agenda) {
                return response()->json([
                    'times' => [],
                    'message' => 'Médico não atende neste dia'
                ]);
            }

            // Gerar array de horários disponíveis com intervalo de 1 hora
            $startTime = Carbon::parse($agenda->start_time);
            $endTime = Carbon::parse($agenda->end_time);
            $availableTimes = [];

            while ($startTime < $endTime) {
                $availableTimes[] = $startTime->format('H:i');
                $startTime->addHour();
            }

            // Buscar consultas já marcadas para este médico neste dia
            $bookedAppointments = Appointment::where('doctor_user_id', $doctorId)
                ->whereDate('appointment_date_time', $date)
                ->get()
                ->map(function ($appointment) {
                    return Carbon::parse($appointment->appointment_date_time)->format('H:i');
                })
                ->toArray();

            // Remover horários já ocupados
            $availableTimes = array_values(array_diff($availableTimes, $bookedAppointments));

            return response()->json($availableTimes);

        } catch (\Exception $e) {
            \Log::error('Erro ao buscar horários disponíveis: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    public function checkAvailability($doctorId, Request $request)
    {
        try {
            $date = $request->query('date');
            if (!$date) {
                return response()->json([
                    'available' => false,
                    'message' => 'Data não fornecida'
                ], 400);
            }

            $dayOfWeek = Carbon::parse($date)->dayOfWeek;

            // Verificar se o médico tem agenda para este dia da semana
            $agenda = UserDoctorAgenda::where('doctor_id', $doctorId)
                ->where('day_of_week', $dayOfWeek)
                ->first();

            if (!$agenda) {
                return response()->json([
                    'available' => false,
                    'message' => 'Médico não atende neste dia da semana'
                ]);
            }

            return response()->json([
                'available' => true,
                'message' => 'Médico disponível neste dia'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao verificar disponibilidade: ' . $e->getMessage());
            return response()->json([
                'available' => false,
                'message' => 'Erro ao verificar disponibilidade: ' . $e->getMessage()
            ], 500);
        }
    }
}
