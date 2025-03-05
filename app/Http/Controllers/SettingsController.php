<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador responsável pelas configurações do utilizador
 */
class SettingsController extends Controller
{
    /**
     * Apresenta a página de configurações do utilizador
     */
    public function index()
    {
        $user = Auth::user()->load('role');
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }



public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => ['required', 'current_password'], // Validação 
        'new_password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    // Atualize a senha
    $user = $request->user();
    $user->update([
        'password' => Hash::make($request->new_password),
    ]);

    return back()->with('success', 'Senha alterada com sucesso.');
}

}
