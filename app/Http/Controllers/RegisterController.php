<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

        // Criar o usuário no banco de dados
        $user = User::create([
            'name' => $validated['name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'birth_date' => $validated['birth_date'],
            'active' => 1, // Usuário ativo por padrão
        ]);

        return redirect()->route('registar.create')->with('success', 'Registro realizado com sucesso!');
    }
    

}


