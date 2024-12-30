<?php

namespace App\Http\Controllers;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class logginController extends Controller
{
    public function index(){
        return view('login.index');
    }

    public function loginProcess(LoginRequest $request): mixed
{
    $request->validated();

    $authenticate = Auth::attempt([
        'email' => $request->email,
        'password' => $request->password,
    ]);

    if (!$authenticate) {
        return back()->withInput()->withErrors(['erro' => 'Email ou Senha inválida']);
    }

    $user = Auth::user();



    // Redirecionamento com base no papel
    if ($user->role->role === 'Patient') {
        return redirect()->route('patient.index');
    } elseif ($user->role->role === 'Doctor') {
        return redirect()->route('doctor.index');
    } else {
        return redirect()->route('index');
    }
}

    public function destroy(){
        Auth::logout();
        return redirect()->route('login');
    }
}