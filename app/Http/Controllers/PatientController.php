<?php

namespace App\Http\Controllers;

use App\Models\DadosPaciente;
use App\Models\DadosPessoais;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class PatientController extends Controller
{
    public function update(Request $request, $id)
    {
        try {
            // Busca o usuário primeiro
            $user = User::findOrFail($id);
            
            // Se o email não foi enviado, usa o email existente do usuário
            if (!$request->has('email')) {
                $request->merge(['email' => $user->email]);
            }

           

            // Validação dos dados
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'data_nascimento' => 'required|date',
                'nif' => 'required|string|max:9',
                'sexo' => 'required|string|in:Masculino,Feminino',
                'estado_civil' => 'required|string|in:Solteiro,Casado,Divorciado',
                'codigo_postal' => 'nullable|string|max:8',
                'morada' => 'nullable|string|max:255',
                'numero' => 'nullable|string|max:20',
                'freguesia' => 'nullable|string|max:100',
                'concelho' => 'nullable|string|max:100',
                'distrito' => 'nullable|string|max:100',
                'grupo_sanguineo' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                'peso' => 'nullable|numeric',
                'altura' => 'nullable|numeric'
            ], [
                'estado_civil.in' => 'O estado civil deve ser Solteiro, Casado ou Divorciado',
                'sexo.in' => 'O sexo deve ser Masculino ou Feminino',
                'grupo_sanguineo.in' => 'O grupo sanguíneo deve ser A+, A-, B+, B-, AB+, AB-, O+ ou O-'
            ]);

            DB::beginTransaction();

            // Atualiza os dados do usuário
            $user->name = $request->name;
            if ($request->has('email') && $request->email !== $user->email) {
                $user->email = $request->email;
            }
            $user->save();

            // Verifica se já existe dados pessoais
            $dadosPessoais = DadosPessoais::where('user_id', $id)->first();

            if (!$dadosPessoais) {
                $dadosPessoais = new DadosPessoais();
                $dadosPessoais->user_id = $id;
            }

            // Atualiza os dados pessoais
            $dadosPessoais->data_nascimento = $request->data_nascimento;
            $dadosPessoais->nif = $request->nif;
            $dadosPessoais->sexo = $request->sexo;
            $dadosPessoais->estado_civil = $request->estado_civil;
            $dadosPessoais->codigo_postal = $request->codigo_postal;
            $dadosPessoais->morada = $request->morada;
            $dadosPessoais->numero = $request->numero;
            $dadosPessoais->freguesia = $request->freguesia;
            $dadosPessoais->concelho = $request->concelho;
            $dadosPessoais->distrito = $request->distrito;
            $dadosPessoais->grupo_sanguineo = $request->grupo_sanguineo;
            $dadosPessoais->peso = $request->peso ? floatval($request->peso) : null;
            $dadosPessoais->altura = $request->altura ? floatval($request->altura) : null;

            $dadosPessoais->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dados atualizados com sucesso',
                'patient' => $user,
                'dados_pessoais' => $dadosPessoais
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
                'data_received' => $request->all() // Para debug
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar paciente: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar os dados: ' . $e->getMessage()
            ], 500);
        }
    }
} 