<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Report;
use Illuminate\Http\Request;

class ConsultaController extends Controller
{
    /**
     * Mostrar a página para iniciar uma consulta.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function iniciarConsulta($id)
    {
        // Busca a consulta pelo ID e carrega os dados do paciente e médico associados à consulta
        $appointment = Appointment::with(['patient', 'doctor'])->findOrFail($id);
        
        // Obtém o utilizador autenticado
        $user = Auth::user();
        
        // Retorna a view para iniciar a consulta, passando os dados da consulta e o utilizador
        return view('appointments.start', compact('appointment','user'));
    }

    /**
     * Salvar o relatório médico da consulta.
     *  
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function salvarRelatorio(Request $request, $id)
    {
        // Valida os campos do formulário
        $request->validate([
            'sintomas' => 'required|string',
            'diagnostico' => 'required|string',
            'tratamento' => 'required|string',
            'observacoes' => 'required|string',
        ]);
    
        // Concatena os campos em um único conteúdo no formato desejado
        $content = "### Sintomas:\n" . $request->input('sintomas') . "\n\n" .
                   "### Diagnóstico:\n" . $request->input('diagnostico') . "\n\n" .
                   "### Tratamento:\n" . $request->input('tratamento') . "\n\n" .
                   "### Observações:\n" . $request->input('observacoes');
    
        // Cria ou atualiza o relatório associado à consulta
        Report::updateOrCreate(
            ['appointment_id' => $id],
            [
                'doctor_report_user_id' => auth()->id(),
                'content' => $content,
                'report_date_time' => now(),
            ]
        );
    
        $appointment = Appointment::find($id);

        if ($appointment) {
            // Define o ID do status "Concluída"
            $completedStatusId = 2;
        
            // Altera apenas o status_id
            $appointment->status_id = $completedStatusId;
        
            // Salva explicitamente a mudança no banco
            $appointment->save();
    
        }
    
        // Redireciona para o painel do médico com uma mensagem de sucesso
        return redirect()->route('doctor.index')->with('success', 'Relatório médico salvo e consulta marcada como concluída!');
    }

    // Exemplo no Controller

}