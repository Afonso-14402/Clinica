<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\ActivityLog; 
use Illuminate\Support\Facades\DB;
use App\Models\DadosPessoais;

/**
 * Controlador responsável pelo registo de novos utilizadores
 */
class RegisterController extends Controller
{
    /**
     * Apresenta o formulário de registo
     */
    public function create()
    {
        $user = Auth::user();
        return view('registar.create', compact('user'));
    }

    /**
     * Regista um novo paciente no sistema
     * Inclui validações e registo de atividade
     */
    public function paciente(Request $request)
    {
        // Validação dos dados do formulário
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            'birth_date' => 'required|date|before:today',
        ]);
        
        // Juntar nome e apelido num campo único
        $fullName = $validated['name'] . ' ' . $validated['last_name'];

        // Criar o utilizador
        $user = User::create([
            'name' => $fullName,  
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'birth_date' => $validated['birth_date'],
            'active' => 1, // Utilizador ativo por predefinição
        ]);

        // Registar atividade
        ActivityLog::create([
            'type' => 'Criação do paciente',
            'description' => "Paciente {$user->name} foi registado no sistema",
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('list.listpatient')->with('success', 'Registo efetuado com sucesso!');
    }
    
    /**
     * Regista um novo paciente através da API
     * Inclui criação de dados pessoais
     */
    public function registarPaciente(Request $request)
    {
        try {
            // Validação dos dados
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
            ]);

            DB::beginTransaction();

            // Criar o utilizador
            $user = User::create([
                'name' => $validated['name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => 3, // ID da função de paciente
            ]);

            // Criar dados pessoais do paciente
            $dadosPessoais = DadosPessoais::create([
                'user_id' => $user->id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Paciente registado com sucesso!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao registar paciente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Regista um novo médico no sistema
     * Inclui associação de especialidades
     */
    public function store(Request $request) {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'birth_date' => 'required|date',
                'password' => 'required|string|min:8|confirmed',
                'role_id' => 'required|integer',
                'specialties' => 'required|array',
            ]);

            // Criar o novo médico
            $doctor = User::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
            ]);

            // Associar especialidades
            $doctor->specialties()->attach($request->specialties);

            return redirect()->back()->with('success', 'Médico registado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}

