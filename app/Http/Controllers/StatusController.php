<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
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

    public function store(Request $request)
    {
        try {
            $request->validate([
                'status' => 'required|string|max:255|unique:status',
                'description' => 'nullable|string'
            ]);

            $status = Status::create($request->all());

            // Log de criação de status
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

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|string|max:255|unique:status,status,' . $id,
                'description' => 'nullable|string'
            ]);

            $status = Status::findOrFail($id);
            $oldStatus = $status->status;
            $status->update($request->all());

            // Log de atualização de status
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

    public function destroy($id)
    {
        try {
            $status = Status::findOrFail($id);
            $statusName = $status->status;
            $status->delete();

            // Log de exclusão de status
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
