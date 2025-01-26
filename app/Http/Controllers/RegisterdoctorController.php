<?php

namespace App\Http\Controllers;
use App\Models\Specialty;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ActivityLog; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'specialty' => 'required|array',
                'specialty.*' => 'exists:specialties,id'
            ]);

            DB::beginTransaction();

            // Criar o usuário
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 1,
                'role_id' => 2 // ID do papel 'Doctor'
            ]);

            // Associar especialidades ao médico
            $user->specialties()->attach($request->specialty);

            DB::commit();

            return response()->json(['message' => 'Médico registrado com sucesso!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    

}


