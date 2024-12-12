<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(){
        $user = Auth::user();
        $user = Auth::user()->load('role');
        return view('index', compact('user'));
    }

    public function updateAvatar(Request $request)
    {
        // Validação do arquivo de imagem
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,gif|max:800', // Máximo de 800 KB
        ]);
    
       
        $user = auth()->user();
    
        // Exclua o avatar antigo, caso exista
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar); 
        }
    
        // Armazenar o novo avatar
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
    
        // Atribua o caminho do avatar ao usuário e salve no banco de dados
        $user->avatar = $avatarPath;
        $user->save(); 
    
        // Retorne para a página anterior com uma mensagem de sucesso
        return redirect()->back()->with('success', 'Avatar atualizado com sucesso!');
    }
    
    

    

}


