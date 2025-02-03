<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Report;
use Illuminate\Http\Request;

/**
 * Controlador responsável pela gestão de consultas médicas em andamento
 */
class ConsultaController extends Controller
{
    /**
     * Apresenta a página para iniciar uma consulta médica
     * Carrega os dados do paciente e médico associados
     *
     * @param int $id Identificador da consulta
     * @return \Illuminate\View\View
     */
    public function iniciarConsulta($id)
    {
        // Obter a consulta com os dados do paciente e médico
        $appointment = Appointment::with(['patient', 'doctor'])->findOrFail($id);
        
        // Obter o utilizador autenticado
        $user = Auth::user();
        
        return view('appointments.start', compact('appointment','user'));
    }

    /**
     * Guarda o relatório médico da consulta
     * Atualiza o estado da consulta para concluída
     *  
     * @param \Illuminate\Http\Request $request
     * @param int $id Identificador da consulta
     * @return \Illuminate\Http\RedirectResponse
     */
    public function salvarRelatorio(Request $request, $id)
    {
        // Validar campos obrigatórios
        $request->validate([
            'sintomas' => 'required|string',
            'diagnostico' => 'required|string',
            'tratamento' => 'required|string',
            'observacoes' => 'required|string',
        ]);
    
        // Formatar o conteúdo do relatório
        $content = "### Sintomas:\n" . $request->input('sintomas') . "\n\n" .
                   "### Diagnóstico:\n" . $request->input('diagnostico') . "\n\n" .
                   "### Tratamento:\n" . $request->input('tratamento') . "\n\n" .
                   "### Observações:\n" . $request->input('observacoes');
    
        // Criar ou atualizar o relatório
        Report::updateOrCreate(
            ['appointment_id' => $id],
            [
                'doctor_report_user_id' => auth()->id(),
                'content' => $content,
                'report_date_time' => now(),
            ]
        );
    
        // Atualizar estado da consulta
        $appointment = Appointment::find($id);
        if ($appointment) {
            $appointment->status_id = 2; // Estado "Concluída"
            $appointment->save();
        }
    
        return redirect()->route('doctor.index')
            ->with('success', 'Relatório médico guardado e consulta marcada como concluída!');
    }
}