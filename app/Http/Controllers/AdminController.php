<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\ActivityLog;

/**
 * Controlador responsável pela gestão de utilizadores administrativos
 */
class AdminController extends Controller
{
    /**
     * Apresenta a lista de todos os utilizadores do sistema
     * Inclui paginação e informações sobre as funções de cada utilizador
     */
    public function index()
    {
        // Obtém todos os utilizadores com as suas funções, ordenados por data de criação
        $users = User::with('role')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                       
        $user = auth()->user();
        
        // Obtém todas as funções disponíveis para o formulário de criação
        $roles = Role::all();
        
        return view('list.listadmin', compact('users', 'user', 'roles'));
    }

    /**
     * Cria um novo utilizador no sistema
     * Requer validação dos dados e regista a atividade no log
     */
    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id'
        ]);

        // Criação do novo utilizador
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'status' => 1
        ]);

        // Registo da atividade no log
        ActivityLog::create([
            'type' => 'criação_utilizador',
            'description' => "Utilizador {$user->name} foi registado no sistema como " . Role::find($user->role_id)->role,
            'user_id' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Utilizador registado com sucesso!');
    }

    /**
     * Alterna o estado do utilizador entre ativo e inativo
     * Regista a alteração no log de atividades
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = !$user->status;
        $user->save();

        // Registo da alteração de estado no log
        ActivityLog::create([
            'type' => 'toggle_status_utilizador',
            'description' => "Estado do utilizador {$user->name} foi alterado para " . ($user->status ? 'ativo' : 'inativo'),
            'user_id' => auth()->id()
        ]);

        return redirect()->back();
    }

    /**
     * Remove um utilizador do sistema
     * Impede a eliminação do próprio utilizador autenticado
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Verificação de segurança para impedir auto-eliminação
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Não é possível eliminar o seu próprio utilizador.');
        }
        
        $userName = $user->name;
        $userRole = $user->role->role;
        
        $user->delete();

        // Registo da eliminação no log
        ActivityLog::create([
            'type' => 'eliminar_utilizador',
            'description' => "Utilizador {$userName} ({$userRole}) foi eliminado do sistema",
            'user_id' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Utilizador eliminado com sucesso!');
    }

    /**
     * Atualiza os dados de um utilizador existente
     * Permite atualização opcional da palavra-passe
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Validação dos dados básicos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id'
        ]);

        // Atualização dos dados do utilizador
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ]);

        // Atualização opcional da palavra-passe
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed'
            ]);
            
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        // Registo da atualização no log
        ActivityLog::create([
            'type' => 'atualizar_utilizador',
            'description' => "Dados do utilizador {$user->name} foram atualizados",
            'user_id' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Utilizador atualizado com sucesso!');
    }
} 