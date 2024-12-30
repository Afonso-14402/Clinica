<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use App\Models\User;
use App\Models\Role;
use App\Models\UserDoctorAgenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ListController extends Controller
{
    // Exibe a lista de médicos
    public function index(Request $request)
{
    $doctors = User::where('role_id', 2)
    ->with('specialties') // Certifique-se de usar o plural para buscar todas as especialidades
    ->when($request->search, function ($query, $search) {
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
    })
    ->paginate(3);
    $specialties = Specialty::all();
    $user = Auth::user();
    return view('list.index', compact('doctors', 'user','specialties'));
}
public function search(Request $request)
{
    $query = $request->get('query', ''); // Using query instead of q
    $specialty = $request->get('specialty', ''); // Specialty filter

    // Start querying the doctors with role_id = 2 (Médicos)
    $doctorsQuery = User::where('role_id', 2);

    // If a name query is provided, filter by doctor's name
    if (!empty($query)) {
        $doctorsQuery->where('name', 'LIKE', "%$query%");
    }

    // If a specialty filter is provided, filter by specialty
    if (!empty($specialty)) {
        $doctorsQuery->whereHas('specialties', function ($q) use ($specialty) {
            $q->where('name', 'LIKE', "%$specialty%");
        });
    }

    // Paginate the results with a limit of 3 doctors per page
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



public function toggleStatus(User $doctor)
{
    // Alternar o status do médico
    $doctor->status = $doctor->status ? 0 : 1; // 0 = inativo, 1 = ativo
    $doctor->save();

    // Retornar com uma mensagem de sucesso
    return redirect()->back()->with('success', 'Status atualizado com sucesso!');
}


    // Exclui o médico
    public function destroy(User $doctor)
    {
        $doctor->delete();

        return redirect()->route('list.index')->with('success', 'Médico excluído com sucesso!');
    }


    public function getPatients(Request $request)
{
    $search = $request->input('search');
    
    $query = User::where('role_id', 3); // Substitua "User" por "Patient" se houver um modelo específico para pacientes.

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%");
        });
    }

    $patients = $query->paginate(2); // Substitua 10 pelo número desejado de itens por página.
    $user = Auth::user();

    if ($request->ajax()) {
        return view('list.partials.patient_table', compact('patients'))->render();
    }

    return view('list.listpatient', compact('patients', 'user'));
}

public function toggleStatusPatients(Request $request, $id)
{
    $patient = User::findOrFail($id);

    // Inverte o status atual
    $patient->status = !$patient->status;
    // Salva no banco
    $patient->save();
    return redirect()->back()->with('success', 'Status atualizado com sucesso!');
}

public function destroyPatients(User $patient)
{
    if (!$patient) {
        return redirect()->route('list.listpatient')->with('error', 'Paciente não encontrado!');
    }

    $patient->delete();

    return redirect()->route('list.listpatient')->with('success', 'Paciente excluído com sucesso!');
}



}


