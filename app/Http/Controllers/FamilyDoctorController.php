<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FamilyDoctor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador responsável pela gestão das relações entre médicos de família e pacientes
 */
class FamilyDoctorController extends Controller
{
    /**
     * Apresenta a lista de todas as relações médico-paciente
     * Inclui opções para criar novas relações
     */
    public function index()
    {
        // Obter todas as relações médico-paciente com seus dados relacionados
        $familyDoctors = FamilyDoctor::with(['patient', 'doctor'])->get();
        $user = Auth::user();

        // Obter pacientes ativos para seleção
        $patients = User::where('role_id', 3)
                       ->where('status', 1)
                       ->get();
        
        // Obter médicos ativos para seleção
        $doctors = User::where('role_id', 2)
                      ->where('status', 1)
                      ->get();

        return view('list.listfamily', [
            'familydoctors' => $familyDoctors,
            'patients' => $patients,
            'doctors' => $doctors,
            'user' => $user
        ]);
    }

    /**
     * Obtém listas de pacientes e médicos disponíveis para criar nova relação
     */
    public function create()
    {
        // Obter apenas utilizadores com função de paciente
        $patients = User::where('role_id', 3)
                       ->where('status', 1)
                       ->get();
        
        // Obter apenas utilizadores com função de médico
        $doctors = User::where('role_id', 2)
                      ->where('status', 1)
                      ->get();

        return response()->json([
            'patients' => $patients,
            'doctors' => $doctors
        ]);
    }

    /**
     * Guarda uma nova relação médico-paciente
     * Verifica se o paciente já tem um médico de família atribuído
     */
    public function store(Request $request)
    {
        // Verificar se já existe uma relação para este paciente
        $existingRelation = FamilyDoctor::where('patient_user_id', $request->patient_id)->first();
        
        if ($existingRelation) {
            return redirect()->back()->with('error', 'Este paciente já possui um médico de família!');
        }

        // Criar nova relação
        $familyDoctor = new FamilyDoctor;
        $familyDoctor->patient_user_id = $request->patient_id;
        $familyDoctor->doctor_user_id = $request->doctor_id;
        $familyDoctor->save();

        return redirect('/family')->with('msg', 'Relação médico-paciente registada com sucesso!');
    }

    /**
     * Apresenta o formulário para editar uma relação existente
     */
    public function edit($id)
    {
        $familyDoctor = FamilyDoctor::findOrFail($id);
        
        // Obter listas de pacientes e médicos disponíveis
        $patients = User::whereHas('role', function($query) {
            $query->where('role', 'Patient');
        })->get();
        
        $doctors = User::whereHas('role', function($query) {
            $query->where('role', 'Doctor');
        })->get();

        return view('family.edit', [
            'familydoctor' => $familyDoctor,
            'patients' => $patients,
            'doctors' => $doctors
        ]);
    }

    /**
     * Atualiza uma relação médico-paciente existente
     * Inclui validações para evitar duplicações
     */
    public function update(Request $request, $id)
    {
        // Validação dos dados
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id'
        ], [
            'patient_id.required' => 'O paciente é obrigatório',
            'doctor_id.required' => 'O médico é obrigatório',
            'patient_id.exists' => 'Paciente inválido',
            'doctor_id.exists' => 'Médico inválido'
        ]);

        $familyDoctor = FamilyDoctor::findOrFail($id);
        
        // Verificar se já existe uma relação para este paciente
        $existingRelation = FamilyDoctor::where('patient_user_id', $request->patient_id)
            ->where('id', '!=', $id)
            ->first();
        
        if ($existingRelation) {
            return redirect()->back()->with('error', 'Este paciente já possui um médico de família!');
        }

        // Atualizar a relação
        $familyDoctor->update([
            'patient_user_id' => $request->patient_id,
            'doctor_user_id' => $request->doctor_id
        ]);

        return redirect()->back()->with('success', 'Relação atualizada com sucesso!');
    }

    /**
     * Remove uma relação médico-paciente
     */
    public function destroy($id)
    {
        FamilyDoctor::findOrFail($id)->delete();
        return redirect('/family')->with('success', 'Relação médico-paciente removida com sucesso!');
    }

    /**
     * Pesquisa relações médico-paciente por nome do médico ou paciente
     */
    public function search(Request $request)
    {
        $search = $request->search;
        
        // Pesquisar por nome do paciente ou médico
        $familyDoctors = FamilyDoctor::whereHas('patient', function($query) use ($search) {
            $query->where('name', 'like', '%'.$search.'%');
        })->orWhereHas('doctor', function($query) use ($search) {
            $query->where('name', 'like', '%'.$search.'%');
        })->with(['patient', 'doctor'])->get();

        // Obter listas para o modal
        $patients = User::where('role_id', 3)
                       ->where('status', 1)
                       ->get();
        
        $doctors = User::where('role_id', 2)
                      ->where('status', 1)
                      ->get();

        return view('list.listfamily', [
            'familydoctors' => $familyDoctors,
            'patients' => $patients,
            'doctors' => $doctors
        ]);
    }
} 