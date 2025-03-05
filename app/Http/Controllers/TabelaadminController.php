<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class TabelaAdminController extends Controller
{
    /**
     * Apresenta a lista de todas as relações médico-paciente
     * Inclui opções para criar novas relações
     */
    public function index()
    {
        $user = auth()->user();
        $admins = User::whereHas('role', function($query) {
            $query->whereIn('role', ['admin', 'doctor', 'patient']);
        })->paginate(10);
        
        return view('list.listadmin', compact('admins', 'user'));
    }

    public function search(Request $request)
    {
        try {
            $query = $request->get('query');
            $user = auth()->user();
            
            $admins = User::whereHas('role', function($q) {
                $q->whereIn('role', ['admin', 'doctor', 'patient']);
            })
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->paginate(10);
            
            return view('list.listadmin', compact('admins', 'user'))
                ->render(); // Importante para retornar apenas o HTML necessário
                
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao realizar a pesquisa: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $admin = User::findOrFail($id);
        return response()->json($admin);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'sometimes|nullable|min:6|confirmed'
        ]);

        $admin = User::findOrFail($id);
        
        $admin->name = $request->name;
        $admin->email = $request->email;
        
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return response()->json([
            'success' => true,
            'message' => 'Administrador atualizado com sucesso!'
        ]);
        
    }

    public function destroy($id)
    {
        $admin = User::findOrFail($id);
        $admin->delete();

        return redirect()->back()->with('success', 'Administrador eliminado com sucesso!');
        
    }

    public function toggleStatus($id)
    {
        $admin = User::findOrFail($id);
        $admin->status = !$admin->status;
        $admin->save();
            
        return redirect()->back()->with('success', 'Status atualizado com sucesso!');
       
    }

    public function store(Request $request)
    {
        try {
            // Validação dos dados
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed'
            ]);

            // Busca o role_id para admin
            $adminRole = Role::where('role', 'admin')->first();
            
            if (!$adminRole) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role de administrador não encontrada'
                ], 400);
            }

            // Cria o novo administrador
            $admin = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $adminRole->id,
                'status' => 1 // Ativo por padrão
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Administrador registrado com sucesso!',
                'admin' => $admin
            ]);

           

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao registrar administrador: ' . $e->getMessage()
            ], 500);
        }
    }
} 