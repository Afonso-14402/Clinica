<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador responsável pela gestão dos estados das consultas
 */
class StatusController extends Controller
{
    /**
     * Obtém o estado de uma consulta específica
     */
    public function showStatus()
    {
        try {
            $status = Status::where('id', 1)->first();
            return response()->json($status);
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar status: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar status'], 500);
        }
    }

    /**
     * Cria um novo estado no sistema
     * Inclui registo de atividade
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados
            $request->validate([
                'status' => 'required|string|max:255|unique:status',
                'description' => 'nullable|string'
            ]);

            // Criar o estado
            $status = Status::create($request->all());

            // Registar atividade
            ActivityLog::create([
                'type' => 'criacao_status',
                'description' => "Novo status '{$status->status}' foi criado",
                'user_id' => Auth::id()
            ]);

            return response()->json(['message' => 'Status criado com sucesso!', 'status' => $status]);
        } catch (\Exception $e) {
            \Log::error('Erro ao criar status: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao criar status'], 500);
        }
    }

    /**
     * Atualiza um estado existente
     * Inclui registo de alteração
     */
    public function update(Request $request, $id)
    {
        try {
            // Validação dos dados
            $request->validate([
                'status' => 'required|string|max:255|unique:status,status,' . $id,
                'description' => 'nullable|string'
            ]);

            $status = Status::findOrFail($id);
            $oldStatus = $status->status;
            $status->update($request->all());

            // Registar alteração
            ActivityLog::create([
                'type' => 'atualizacao_status',
                'description' => "Status alterado de '{$oldStatus}' para '{$status->status}'",
                'user_id' => Auth::id()
            ]);

            return response()->json(['message' => 'Status atualizado com sucesso!', 'status' => $status]);
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar status: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao atualizar status'], 500);
        }
    }

    /**
     * Remove um estado do sistema
     * Inclui registo de eliminação
     */
    public function destroy($id)
    {
        try {
            $status = Status::findOrFail($id);
            $statusName = $status->status;
            $status->delete();

            // Registar eliminação
            ActivityLog::create([
                'type' => 'exclusao_status',
                'description' => "Status '{$statusName}' foi excluído",
                'user_id' => Auth::id()
            ]);

            return response()->json(['message' => 'Status excluído com sucesso!']);
        } catch (\Exception $e) {
            \Log::error('Erro ao excluir status: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao excluir status'], 500);
        }
    }
}
