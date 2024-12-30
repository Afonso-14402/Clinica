<?php

namespace App\Http\Controllers;
use App\Models\Specialty;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ActivityLog; 

class RegisterdoctorController extends Controller
{
    public function create()
    {
        $user = Auth::user()->load('role'); // Carrega a relação 'role' do usuário autenticado
        $specialties = Specialty::all(); // Carrega todas as especialidades do banco de dados
    
        return view('registar.create-m', compact('user', 'specialties'));
    }
    


    public function medico(Request $request)
{
    // Validar os dados do formulário
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed|min:6',
        'specialties' => 'required|array', // Deve conter IDs das especialidades
        'specialties.*' => 'exists:specialties,id',
    ]);

    // Juntar o nome e o sobrenome em um campo único 'full_name'
    $fullName = $validatedData['name'] . ' ' . $validatedData['last_name'];

    // Criar o novo usuário (médico)
    $user = User::create([
        'name' => $fullName,
        'email' => $validatedData['email'],
        'password' => bcrypt($validatedData['password']),
        'role_id' => 2, // Definindo o 'role_id' para Médico
    ]);
    ActivityLog::create([
        'type' => 'criaçao do medico', // Tipo da ação
        'description' => "Novo medico {$user->name} foi registrado no sistema", // Use $user->name para acessar o nome do usuário
        'user_id' => auth()->id(), // Usuário autenticado que realizou a ação
    ]);

    // Associar as especialidades ao usuário recém-criado
    $user->specialties()->attach($validatedData['specialties']);

    // Redirecionar com mensagem de sucesso
    return redirect()->route('list.index')->with('success', 'Médico registrado com sucesso!');
}


    

}


