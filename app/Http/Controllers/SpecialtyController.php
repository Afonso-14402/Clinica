<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpecialtyController extends Controller
{
    public function getSpecialties($doctorId)
    {
        try {
            $specialties = Specialty::whereHas('usersSpecialties', function ($query) use ($doctorId) {
                $query->where('user_id', $doctorId);
            })->get();

            return $specialties;
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar especialidades: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar especialidades'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:specialties',
                'description' => 'nullable|string'
            ]);

            $specialty = Specialty::create($request->all());

            // Log de criação de especialidade
            ActivityLog::create([
                'type' => 'criacao_especialidade',
                'description' => "Nova especialidade '{$specialty->name}' foi criada",
                'user_id' => Auth::id()
            ]);

            return response()->json(['message' => 'Especialidade criada com sucesso!', 'specialty' => $specialty]);
        } catch (\Exception $e) {
            \Log::error('Erro ao criar especialidade: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao criar especialidade'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:specialties,name,' . $id,
                'description' => 'nullable|string'
            ]);

            $specialty = Specialty::findOrFail($id);
            $oldName = $specialty->name;
            $specialty->update($request->all());

            // Log de atualização de especialidade
            ActivityLog::create([
                'type' => 'atualizacao_especialidade',
                'description' => "Especialidade alterada de '{$oldName}' para '{$specialty->name}'",
                'user_id' => Auth::id()
            ]);

            return response()->json(['message' => 'Especialidade atualizada com sucesso!', 'specialty' => $specialty]);
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar especialidade: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao atualizar especialidade'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $specialty = Specialty::findOrFail($id);
            $specialtyName = $specialty->name;
            $specialty->delete();

            // Log de exclusão de especialidade
            ActivityLog::create([
                'type' => 'exclusao_especialidade',
                'description' => "Especialidade '{$specialtyName}' foi excluída",
                'user_id' => Auth::id()
            ]);

            return response()->json(['message' => 'Especialidade excluída com sucesso!']);
        } catch (\Exception $e) {
            \Log::error('Erro ao excluir especialidade: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao excluir especialidade'], 500);
        }
    }
}
