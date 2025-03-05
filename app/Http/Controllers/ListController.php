<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Specialty;
use App\Models\User;
use App\Models\Role;
use App\Models\UserDoctorAgenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador responsável pela gestão de listagens de médicos e pacientes
 */
class ListController extends Controller
{
    /**
     * Apresenta a lista de médicos com opções de pesquisa
     */
    public function index(Request $request)
    {
        // Obtém os médicos com as suas especialidades
        $doctors = User::where('role_id', 2)
            ->with('specialties')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->paginate(3);
        $specialties = Specialty::all();
        $user = Auth::user();
        return view('list.index', compact('doctors', 'user', 'specialties'));
    }

    /**
     * Procura médicos com base em critérios específicos
     */
    public function search(Request $request)
    {
        // Obter os parâmetros da pesquisa enviados 
        $query = $request->get('query', ''); 
        $specialty = $request->get('specialty', ''); 

        // Iniciar uma consulta para os utilizadores com role_id = 2 (Médicos)
        $doctorsQuery = User::where('role_id', 2);

        // Filtrar pelo nome do médico se fornecido
        if (!empty($query)) {
            $doctorsQuery->where('name', 'LIKE', "%$query%");
        }

        // Filtrar pela especialidade se fornecida
        if (!empty($specialty)) {
            $doctorsQuery->whereHas('specialties', function ($q) use ($specialty) {
                $q->where('name', 'LIKE', "%$specialty%");
            });
        }

        $doctors = $doctorsQuery->with('specialties')->paginate(3);

        return response()->json([
            'success' => true, 
            'data' => $doctors->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'email' => $doctor->email,
                    'role_id' => $doctor->role_id,
                    'status' => $doctor->status, 
                    'created_at' => $doctor->created_at, 
                    'updated_at' => $doctor->updated_at, 
                    'avatar' => $doctor->avatar, 
                    'specialties' => $doctor->specialties->map(function ($specialty) {
                        return [
                            'id' => $specialty->id,
                            'name' => $specialty->name,
                        ];
                    }),
                ];
            }),
            'pagination' => [
                'current_page' => $doctors->currentPage(),
                'last_page' => $doctors->lastPage(),
                'total' => $doctors->total(),
            ],
        ]);
    }

    /**
     * Alterna o estado de ativo/inativo de um médico
     */
    public function toggleStatus(User $doctor)
    {
        $doctor->status = $doctor->status ? 0 : 1;
        $doctor->save();

        return redirect()->back()->with('success', 'Estado atualizado com sucesso!');
    }

    /**
     * Remove um médico do sistema
     */
    public function destroy(User $doctor)
    {
        $doctor->delete();
        return redirect()->route('list.index')->with('success', 'Médico eliminado com sucesso!');
    }

    /**
     * Obtém a lista de pacientes com opções de pesquisa
     */
    public function getPatients(Request $request)
    {
        $search = $request->input('search');
       
        // Consulta base para pacientes
        $query = User::where('role_id', 3)
            ->with(['dados_pessoais']);

        // Aplicar filtro de pesquisa se fornecido
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $patients = $query->orderBy('name', 'asc')->paginate(10);
        $user = Auth::user();

        if ($request->ajax()) {
            return view('list.partials.patient_table', compact('patients'))->render();
        }

        return view('list.listpatient', compact('patients', 'user'));
    }

    /**
     * Alterna o estado de ativo/inativo de um paciente
     */
    public function toggleStatusPatients(User $patient)
    {
        $patient->status = $patient->status ? 0 : 1;
        $patient->save();

        return redirect()->back()->with('success', 'Estado atualizado com sucesso!');
    }

    /**
     * Remove um paciente do sistema
     */
    public function destroyPatients(User $patient)
    {
        if (!$patient) {
            return redirect()->route('list.listpatient')->with('error', 'Paciente não encontrado!');
        }

        $patient->delete();

        return redirect()->route('list.listpatient')->with('success', 'Paciente eliminado com sucesso!');
    }

    /**
     * Obtém os relatórios médicos de um paciente específico
     */
    public function getPatientReports($patientId)
    {
        $reports = Report::whereHas('appointment', function($query) use ($patientId) {
            $query->where('patient_user_id', $patientId);
        })->get();

        return response()->json($reports->map(function($report) {
            return [
                'report_date_time' => $report->created_at->format('Y-m-d H:i:s'),
                'content' => $report->content,
            ];
        }));
    }

    /**
     * Obtém os detalhes pessoais de um paciente específico
     */
    public function getPatientDetails($id)
    {
        try {
            $patient = User::with('dados_pessoais')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'dados_pessoais' => [
                    'data_nascimento' => $patient->dados_pessoais->data_nascimento ?? null,
                    'nif' => $patient->dados_pessoais->nif ?? null,
                    'sexo' => $patient->dados_pessoais->sexo ?? null,
                    'estado_civil' => $patient->dados_pessoais->estado_civil ?? null,
                    'morada' => $patient->dados_pessoais->morada ?? null,
                    'numero' => $patient->dados_pessoais->numero ?? null,
                    'codigo_postal' => $patient->dados_pessoais->codigo_postal ?? null,
                    'freguesia' => $patient->dados_pessoais->freguesia ?? null,
                    'concelho' => $patient->dados_pessoais->concelho ?? null,
                    'distrito' => $patient->dados_pessoais->distrito ?? null,
                    'grupo_sanguineo' => $patient->dados_pessoais->grupo_sanguineo ?? null,
                    'peso' => $patient->dados_pessoais->peso ?? null,
                    'altura' => $patient->dados_pessoais->altura ?? null
                ],
                'patient' => [
                    'name' => $patient->name,
                    'email' => $patient->email
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar dados do paciente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $doctor = User::with(['specialties', 'role'])
            ->whereHas('role', function($query) {
                $query->where('role', 'Médico');
            })
            ->findOrFail($id);
        
        $specialties = Specialty::all();
        
        return view('list.edit', compact('doctor', 'specialties'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'specialties' => 'required|array',
            'status' => 'required|in:active,inactive',
        ]);

        $doctor = User::findOrFail($id);
        
        $doctor->update([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status === 'active' ? 1 : 0,
        ]);

        $doctor->specialties()->sync($request->specialties);

        return redirect()->back()->with('success', 'Médico atualizado com sucesso!');
    }
}


