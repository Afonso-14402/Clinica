<?php

namespace App\Http\Controllers;
use App\Models\Specialty;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ActivityLog; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Controlador responsável pelo registo de novos médicos no sistema
 */
class RegisterdoctorController extends Controller
{
    /**
     * Apresenta o formulário de registo de médico
     * Carrega as especialidades disponíveis
     */
    public function create()
    {
        try {
            $user = Auth::user()->load('role');
            $specialties = Specialty::all();
        
            return view('registar.create-m', compact('user', 'specialties'));
        } catch (\Exception $e) {
            \Log::error('Erro ao acessar formulário de registro de médico: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao acessar o formulário. Tente novamente.');
        }
    }

    /**
     * Regista um novo médico no sistema
     * Inclui validações, associação de especialidades e registo de atividade
     */
    public function medico(Request $request)
    {
        try {
            // Validação dos dados
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'specialty' => 'required|array',
                'specialty.*' => 'exists:specialties,id'
            ]);

            DB::beginTransaction();

            // Criar o utilizador com função de médico
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 1,
                'role_id' => 2 // ID do papel 'Doctor'
            ]);

            // Associar especialidades ao médico
            $user->specialties()->attach($request->specialty);

            // Buscar nomes das especialidades para o log
            $specialtyNames = Specialty::whereIn('id', $request->specialty)->pluck('name')->implode(', ');

            // Registar atividade
            ActivityLog::create([
                'type' => 'criacao_medico',
                'description' => "Médico {$user->name} foi registrado no sistema com as especialidades: {$specialtyNames}",
                'user_id' => Auth::id()
            ]);

            DB::commit();

            return response()->json(['message' => 'Médico registrado com sucesso!']);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao registrar médico: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}


