<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }



public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => ['required', 'current_password'], // Validação com a regra built-in
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
