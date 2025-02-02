<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\ActivityLog; 
use Illuminate\Support\Facades\DB;
use App\Models\DadosPessoais;

class RegisterController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        return view('registar.create', compact('user'));
      
    }

    public function paciente(Request $request)
    {
        // Validação dos dados enviados pelo formulário
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            'birth_date' => 'required|date|before:today',
        ]);
        
        // Juntar o nome e o sobrenome em um campo único 'full_name'
        $fullName = $validated['name'] . ' ' . $validated['last_name'];

        // Criar o usuário no banco de dados
        $user = User::create([
            'name' => $fullName,  
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'birth_date' => $validated['birth_date'],
            'active' => 1, // Usuário ativo por padrão
        ]);
        ActivityLog::create([
            'type' => 'Criaçao do paciente', // Tipo da ação
            'description' => "Paciente {$user->name} foi registrado no sistema", // Use $user->name para acessar o nome do usuário
            'user_id' => auth()->id(), // Usuário autenticado que realizou a ação
        ]);
        

        return redirect()->route('list.listpatient')->with('success', 'Registro realizado com sucesso!');
    }
    
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

            // Criar o usuário
            $user = User::create([
                'name' => $validated['name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => 3, // ID do papel de paciente
            ]);

            // Criar dados pessoais do paciente
            $dadosPessoais = DadosPessoais::create([
                'user_id' => $user->id,
                // Adicione outros campos conforme necessário
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

            // Criação do novo médico
            $doctor = User::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
            ]);

            // Associar especialidades
            $doctor->specialties()->attach($request->specialties);

            return redirect()->back()->with('success', 'Médico registrado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}

