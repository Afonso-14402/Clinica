<?php

namespace App\Http\Controllers;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador responsável pela autenticação de utilizadores
 */
class logginController extends Controller
{
    /**
     * Apresenta o formulário de autenticação
     */
    public function index()
    {
        return view('login.index');
    }

    /**
     * Processa a tentativa de autenticação
     * Redireciona com base na função do utilizador
     * 
     * @param LoginRequest $request
     * @return mixed
     */
    public function loginProcess(LoginRequest $request): mixed
    {
        $request->validated();

        // Tentar autenticar o utilizador
        $authenticate = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if (!$authenticate) {
            return back()->withInput()->withErrors(['erro' => 'Email ou Palavra-passe inválida']);
        }

        $user = Auth::user();

        // Redirecionar com base na função do utilizador
        if ($user->role->role === 'Patient') {
            return redirect()->route('patient.index');
        } elseif ($user->role->role === 'Doctor') {
            return redirect()->route('doctor.index');
        } else {
            return redirect()->route('index');
        }
    }

    /**
     * Termina a sessão do utilizador
     */
    public function destroy()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}