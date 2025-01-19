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
        $doctorId = $request->input('doctor_id');
        $day = $request->input('day');

        // Validar os parâmetros
        if (!$doctorId || !$day) {
            return response()->json(['error' => 'Parâmetros inválidos'], 400);
        }

        // Converter o dia para o formato apropriado e obter o dia da semana
        $parsedDay = Carbon::createFromFormat('Y-m-d', $day);
        $dayOfWeek = $parsedDay->dayOfWeek;

        // Obter a agenda do médico para o dia da semana
        $agenda = UserDoctorAgenda::where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$agenda) {
            return response()->json(['error' => 'Médico indisponível neste dia'], 404);
        }

        // Listar os horários disponíveis com intervalos de 1 hora
        $startTime = Carbon::parse($agenda->start_time);
        $endTime = Carbon::parse($agenda->end_time);
        $availableTimes = [];

        while ($startTime->lt($endTime)) {
            $availableTimes[] = $startTime->format('H:i');
            $startTime->addHour(); // Intervalos de 1 hora
        }

        // Obter os horários ocupados
        $existingAppointments = Appointment::where('doctor_user_id', $doctorId)
            ->whereDate('appointment_date_time', $parsedDay)
            ->pluck('appointment_date_time');

        // Mapear os horários ocupados para o formato "H:i"
        $existingTimes = $existingAppointments->map(function ($datetime) {
            return Carbon::parse($datetime)->format('H:i');
        })->toArray();

        // Filtrar os horários disponíveis
        $finalAvailableTimes = array_diff($availableTimes, $existingTimes);

        // Retornar os horários disponíveis
        return response()->json(array_values($finalAvailableTimes)); // Garantir que seja um array puro
    } catch (\Exception $e) {
        \Log::error('Erro ao obter horários disponíveis', [
            'error' => $e->getMessage(),
            'stack' => $e->getTraceAsString(),
        ]);
        return response()->json(['error' => 'Erro ao obter horários disponíveis'], 500);
    }
}

}
