<?php

namespace App\Http\Controllers;
use App\Models\Specialty;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

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
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed|min:6',
        'specialties' => 'required|array', // Deve conter IDs das especialidades
        'specialties.*' => 'exists:specialties,id',
    ]);

    // Criar o novo usuário (médico)
    $user = User::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'password' => bcrypt($validatedData['password']),
        'role_id' => 2, // Definindo o 'role_id' para Médico
    ]);

    // Associar as especialidades ao usuário recém-criado
    $user->specialties()->attach($validatedData['specialties']);

    

    // Redirecionar com mensagem de sucesso
    return redirect()->route('registar.create-m')->with('success', 'Médico registrado com sucesso!');
}
    

}


