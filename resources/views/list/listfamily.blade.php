@extends('layouts.admin')
@section('title', 'Lista de Médicos de Família')
@section('content')
<!-- Navbar -->
<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme shadow-sm" id="layout-navbar">
    <!-- Navbar Content -->
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
           
            <!-- User Profile -->
            <li class="nav-item dropdown-user navbar-dropdown dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}" alt="User Avatar" class="avatar">
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item">
                            <div class="d-flex">
                                <div class="avatar avatar-online me-3">
                                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}" alt="User Avatar">
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                    <small class="text-muted">{{ $user->role->role }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('settings.index') }}">
                            <i class="bx bx-cog me-2"></i>
                            <span>Configurações</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('login.destroy') }}">
                            <i class="bx bx-power-off me-2"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="card">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            Lista de Médicos de Família
            
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         


            

            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createFamilyModal">
                Nova Relação
            </button>
        </h5>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Médico de Família</th>
                        <th>Data de Associação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($familydoctors as $familydoctor)
                    <tr>
                        <td>{{ $familydoctor->patient->name }}</td>
                        <td>{{ $familydoctor->doctor->name }}</td>
                        <td>{{ date('d/m/Y', strtotime($familydoctor->created_at)) }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Ações
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editFamilyModal{{ $familydoctor->id }}">
                                            <i class="bx bx-edit me-1"></i> Editar
                                        </a>
                                    </li>
                                    <li>
                                        <form action="/family/{{ $familydoctor->id }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta relação?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bx bx-trash me-1"></i> Excluir
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if(count($familydoctors) == 0)
                <div class="text-center p-3">
                    <p>Não há relações médico-paciente cadastradas!</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para criar nova relação -->
<div class="modal fade" id="createFamilyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Relação Médico-Paciente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('/family') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col mb-3">
                            <label for="patient_id" class="form-label">Paciente</label>
                            <select class="form-select" id="patient_id" name="patient_id" required>
                                <option value="">Selecione um paciente</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="doctor_id" class="form-label">Médico</label>
                            <select class="form-select" id="doctor_id" name="doctor_id" required>
                                <option value="">Selecione um médico</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar relação -->
@foreach($familydoctors as $familydoctor)
<div class="modal fade" id="editFamilyModal{{ $familydoctor->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Relação Médico-Paciente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('/family/' . $familydoctor->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col mb-3">
                            <label for="edit_patient_id{{ $familydoctor->id }}" class="form-label">Paciente</label>
                            <select class="form-select" id="edit_patient_id{{ $familydoctor->id }}" name="patient_id" required>
                                <option value="{{ $familydoctor->patient_user_id }}" selected>{{ $familydoctor->patient->name }}</option>
                                @foreach($patients as $patient)
                                    @if($patient->id != $familydoctor->patient_user_id)
                                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="edit_doctor_id{{ $familydoctor->id }}" class="form-label">Médico</label>
                            <select class="form-select" id="edit_doctor_id{{ $familydoctor->id }}" name="doctor_id" required>
                                <option value="{{ $familydoctor->doctor_user_id }}" selected>{{ $familydoctor->doctor->name }}</option>
                                @foreach($doctors as $doctor)
                                    @if($doctor->id != $familydoctor->doctor_user_id)
                                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Inicializa Select2 para pacientes
    $('#patient_id').select2({
        dropdownParent: $('#createFamilyModal'),
        ajax: {
            url: '/autocomplete/patient',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        },
        placeholder: 'Selecione um paciente',
        minimumInputLength: 1,
        width: '100%'
    });

    // Inicializa Select2 para médicos
    $('#doctor_id').select2({
        dropdownParent: $('#createFamilyModal'),
        ajax: {
            url: '/autocomplete/doctors',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        },
        placeholder: 'Selecione um médico',
        minimumInputLength: 1,
        width: '100%'
    });

    // Pesquisa na tabela
    $('#familySearch').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Inicializa Select2 para os campos de edição
    @foreach($familydoctors as $familydoctor)
        $(`#edit_patient_id{{ $familydoctor->id }}`).select2({
            dropdownParent: $(`#editFamilyModal{{ $familydoctor->id }}`),
            width: '100%'
        });

        $(`#edit_doctor_id{{ $familydoctor->id }}`).select2({
            dropdownParent: $(`#editFamilyModal{{ $familydoctor->id }}`),
            width: '100%'
        });
    @endforeach
});
</script>
@endsection

@section('styles')
<style>
.select2-container {
    width: 100% !important;
}

.select2-selection {
    height: 38px !important;
    padding: 5px !important;
}

.select2-container--default .select2-selection--single {
    border: 1px solid #ddd;
    border-radius: 4px;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 28px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
}
</style>
@endsection
