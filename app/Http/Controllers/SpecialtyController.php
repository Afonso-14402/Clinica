<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador responsável pela gestão das especialidades médicas
 */
class SpecialtyController extends Controller
{
    /**
     * Obtém as especialidades de um médico específico
     * 
     * @param int $doctorId Identificador do médico
     * @return mixed
     */
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

    /**
     * Cria uma nova especialidade no sistema
     * Inclui registo de atividade
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados
            $request->validate([
                'name' => 'required|string|max:255|unique:specialties',
                'description' => 'nullable|string'
            ]);

            // Criar a especialidade
            $specialty = Specialty::create($request->all());

            // Registar atividade
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

    /**
     * Atualiza uma especialidade existente
     * Inclui registo de alteração
     */
    public function update(Request $request, $id)
    {
        try {
            // Validação dos dados
            $request->validate([
                'name' => 'required|string|max:255|unique:specialties,name,' . $id,
                'description' => 'nullable|string'
            ]);

            $specialty = Specialty::findOrFail($id);
            $oldName = $specialty->name;
            $specialty->update($request->all());

            // Registar alteração
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

    /**
     * Remove uma especialidade do sistema
     * Inclui registo de eliminação
     */
    public function destroy($id)
    {
        try {
            $specialty = Specialty::findOrFail($id);
            $specialtyName = $specialty->name;
            $specialty->delete();

            // Registar eliminação
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
