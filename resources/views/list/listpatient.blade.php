@extends('layouts.admin')

@section('title', 'Lista de Médicos')

<style>
.modal-body {
    background-color: #f9f9f9;
    border-radius: 6px;
    padding: 20px;
}

.list-group-item {
    border: none;
    padding: 15px 0;
}

.list-group-item:not(:last-child) {
    border-bottom: 1px solid #e9ecef;
}

.form-check-label {
    font-weight: bold;
    margin-right: 15px;
}

.status-icon {
    font-size: 1.2rem;
    vertical-align: middle;
    transition: all 0.3s ease;
    cursor: pointer;
}

.status-icon.status-active {
    color: #28a745;
}

.status-icon.status-inactive {
    color: #ffc107;
}

.status-icon:hover {
    transform: scale(1.2);
}

.table th, .table td {
    vertical-align: middle;
    text-align: center;
}

.status-icon {
    font-size: 1.2rem;
    vertical-align: middle;
    transition: all 0.3s ease;
    cursor: pointer;
}

.status-icon:hover {
    transform: scale(1.2);
}

form button {
    cursor: pointer;
}

/* Container geral do formulário */
.schedule-form-container {
    max-width: 700px;
    margin: 2rem auto;
    padding: 2rem;
    border: 1px solid #e3e3e3;
    border-radius: 10px;
    background-color: #ffffff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    font-family: 'Arial', sans-serif;
}

/* Título e subtítulo */
.form-title {
    font-size: 1.8rem;
    font-weight: bold;
    color: #2c3e50;
    text-align: center;
    margin-bottom: 0.5rem;
}

.form-subtitle {
    font-size: 1rem;
    color: #7f8c8d;
    text-align: center;
    margin-bottom: 1.5rem;
}

/* Lista de itens */
.schedule-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* Cada item do horário */
.schedule-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #f9f9f9;
    padding: 1rem;
    border: 1px solid #dcdcdc;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.schedule-item:hover {
    background-color: #f0f0f0;
    border-color: #cccccc;
}

/* Checkbox e label do dia */
.day-checkbox {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-check-label {
    font-weight: 600;
    font-size: 1rem;
    color: #34495e;
}

/* Campos de horário */
.time-inputs {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.time-field {
    width: 120px;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 0.9rem;
}

.time-separator {
    font-size: 0.9rem;
    color: #7f8c8d;
    font-weight: bold;
}

/* Botão remover */
.btn-remove {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    font-weight: bold;
    color: #e74c3c;
    background-color: transparent;
    border: 1px solid #e74c3c;
    border-radius: 5px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-remove:hover {
    background-color: #e74c3c;
    color: #fff;
}

/* medico modal */
        .modal {
            display: none; /* Modal começa oculto */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .custom-modal {
            background-color: rgb(239, 239, 239);
            padding: 20px;
            border-radius: 8px;
            width: 150%;           /* Reduz a largura para 80% da tela */
            max-width: 700px;     /* Aumenta a largura máxima para 700px */
        }

.prontuario-container {
    padding: 1rem;
    background-color: #fff;
}

.section-title {
    color: #2c3e50;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f8f9fa;
}

.table {
    font-size: 0.9rem;
    background-color: #fff;
}

.table th {
    font-weight: 600;
    color: #2c3e50;
    border-bottom: 2px solid #f8f9fa;
}

.table td {
    vertical-align: middle;
    border-bottom: 1px solid #f8f9fa;
}

.btn-view-report {
    padding: 0.25rem 0.75rem;
    font-size: 0.875rem;
    background-color: #fff;
    border: 1px solid #e9ecef;
    color: #3498db;
}

.btn-view-report:hover {
    background-color: #3498db;
    color: #fff;
    border-color: #3498db;
}

.report-metadata {
    font-size: 0.9rem;
}

.report-content {
    font-size: 0.95rem;
    line-height: 1.6;
    color: #2c3e50;
    background-color: #fff;
}

.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}

.btn-outline-secondary {
    border-color: #dee2e6;
    background-color: #fff;
}

.btn-outline-secondary:hover {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #2c3e50;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.card {
    background-color: #fff;
    border-radius: 0.5rem;
}

.modal-content {
    background-color: #fff;
}

.modal-body {
    background-color: #fff;
}

.tab-content {
    background-color: #fff;
}

.tab-pane {
    background-color: #fff;
}

/* Estilos para o modal de novo paciente */
.modal-content {
    border: none;
    border-radius: 8px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-header {
    background-color: #fff;
    border-bottom: 1px solid #e9ecef;
    padding: 1rem 1.5rem;
}

.modal-title {
    color: #2c3e50;
    font-size: 1.1rem;
}

.modal-body {
    padding: 1.5rem;
}

.form-label {
    font-size: 0.875rem;
    color: #495057;
    font-weight: 500;
}

.form-control {
    border: 1px solid #e9ecef;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}

.input-group-text {
    background-color: #fff;
    border: 1px solid #e9ecef;
}

.toggle-password {
    border-color: #e9ecef;
}

.toggle-password:hover {
    background-color: #f8f9fa;
}

.btn {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
}

.btn-primary {
    background-color: #3498db;
    border-color: #3498db;
}

.btn-primary:hover {
    background-color: #2980b9;
    border-color: #2980b9;
}

.btn-outline-secondary {
    color: #6c757d;
    border-color: #e9ecef;
}

.btn-outline-secondary:hover {
    background-color: #f8f9fa;
    color: #495057;
}

h6.fw-bold {
    color: #2c3e50;
    font-size: 0.95rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e9ecef;
}

.bx {
    font-size: 1.1rem;
    vertical-align: middle;
}
</style>

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
            <!-- Notifications -->
            <li class="nav-item dropdown-notifications navbar-dropdown dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="bx bx-bell bx-sm"></i>
                    <span class="badge rounded-pill bg-danger badge-notifications"></span>
                </a>
            </li>
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
    <div class="card">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            
            Lista de Pacientes
            


            <div class="col-md-4">
                <input type="text" id="patientSearch" class="form-control" placeholder="Pesquisar por nome...">
            </div>

            <button id="exportButton" class="btn btn-success btn-sm">
                <i class="bx bx-download"></i> Exportar para Excel
            </button>

            <button id="openModal" class="btn btn-primary">Novo paciente </button>
            
            
        </h5>
        <div class="table-responsive text-nowrap" id="doctorTable">
            <table class="table">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="patientTableBody">
                    @foreach($patients as $patient)
                    <tr>
                        <td>{{ $patient->id }}</td>
                        <td>{{ $patient->name }}</td>
                        <td>{{ $patient->email }}</td>
                        
                        
                        <!-- Status do paciente   -->
                        <td>
                            <form action="{{ route('patients.toggle-status', $patient->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" style="border: none; background: none; padding: 0;">
                                    <i class="status-icon {{ $patient->status ? 'status-active bx bx-check-circle' : 'status-inactive bx bx-x-circle' }}" data-toggle="tooltip" title="{{ $patient->status ? 'Ativo - Clique para desativar' : 'Inativo - Clique para ativar' }}"></i>
                                </button>
                            </form>
                        </td>
                        
                        
                        <!-- Ações -->
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Ações
                                </button>
                                <ul class="dropdown-menu">
                                    <!-- Editar Paciente -->
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-edit-alt me-1"></i> Editar
                                        </a>
                                    </li>
                                    
                                    <!-- Ver Prontuário -->
                                    <li>
                                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addPacienteModal" data-patient-id="{{ $patient->id }}">
                                            <i class="bx bx-file me-1"></i> Ver Prontuário
                                        </a>
                                    </li>
                                    
                                    <!-- Excluir Paciente -->
                                    <li>
                                        <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" onsubmit="return confirm('Tem a certeza que pretende eliminar este utente?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bx bx-trash me-1"></i> Eliminar
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
            <nav>
                <ul class="pagination justify-content-center">
                    {{-- Link para a página anterior --}}
                    @if ($patients->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">Anterior</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $patients->previousPageUrl() }}">Anterior</a>
                        </li>
                    @endif
                
                    {{-- Links para as páginas --}}
                    @for ($i = 1; $i <= $patients->lastPage(); $i++)
                        <li class="page-item {{ $patients->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $patients->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                
                    {{-- Link para a próxima página --}}
                    @if ($patients->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $patients->nextPageUrl() }}">Próxima</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">Próxima</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>
   <!-- Modal para Novo Paciente -->
   <div class="modal" id="novopacienteModal" tabindex="-1"> 
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-user-plus me-2"></i>Registar Novo Paciente
                </h5>
                <button type="button" class="btn-close" id="closeModal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-white">
                <form action="{{ route('registar.paciente') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <!-- Informações Pessoais -->
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-bold mb-3">
                                <i class="bx bx-user me-2"></i>Dados Pessoais
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nome Próprio</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Apelido</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="last_name" 
                                   name="last_name" 
                                   value="{{ old('last_name') }}" 
                                   required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required>
                        </div>
                        
                    </div>

                    <!-- Dados de Acesso -->
                    <div class="row g-3">
                        <div class="col-12">
                            <h6 class="fw-bold mb-3">
                                <i class="bx bx-lock me-2"></i> Dados de Acesso
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Palavra-passe</label>
                            <div class="input-group">
                                <input type="text" 
                                    class="form-control"
                                       id="password" 
                                    name="password"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirmar Palavra-passe</label>
                            <div class="input-group">
                                <input type="text" 
                                    class="form-control"
                                       id="password_confirmation" 
                                    name="password_confirmation"
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-4">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-check me-1"></i>Registar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>  


<!-- Modal -->
<div class="modal fade" id="addPacienteModal" tabindex="-1" aria-labelledby="addPacienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addPacienteModalLabel">Adicionar Paciente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Formulário com abas -->
          <div class="card shadow-sm">
            <div class="card-header">
              <ul class="nav nav-tabs card-header-tabs" id="pacienteModalTab" role="tablist">
                @foreach (['Dados Pessoais', 'Prontuário Médico'] as $index => $tab)
                <li class="nav-item" role="presentation">
                  <button 
                    class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                    id="modal-{{ Str::slug($tab) }}-tab" 
                    data-bs-toggle="tab" 
                    data-bs-target="#modal-{{ Str::slug($tab) }}" 
                    type="button" 
                    role="tab">
                    {{ $tab }}
                  </button>
                </li>
                @endforeach
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content" id="pacienteModalTabContent">
                <!-- Aba: Dados Pessoais -->
                <div class="tab-pane fade show active" id="modal-dados-pessoais" role="tabpanel">
                    <div class="row">
                        <!-- Informações Básicas -->
                      <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nome Completo</label>
                            <p>{{ $patient->name }}</p>
                      </div>
                      <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Data de Nascimento</label>
                            <p>{{ $patient->dados_pessoais->data_nascimento ?? 'Não informado' }}</p>
                      </div>
                        
                        <!-- Documentos -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">NIF</label>
                            <p>{{ $patient->dados_pessoais->nif ?? 'Não informado' }}</p>
                    </div>
                        
                        <!-- Informações Pessoais -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Sexo</label>
                            <p>{{ $patient->dados_pessoais->sexo ?? 'Não informado' }}</p>
                    </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Estado Civil</label>
                            <p>{{ $patient->dados_pessoais->estado_civil ?? 'Não informado' }}</p>
                        </div>
                        
                        <!-- Endereço -->
                        <div class="col-md-12 mb-3">
                            <h6 class="fw-bold">Endereço</h6>
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Morada</label>
                                    <p>{{ $patient->dados_pessoais->morada ?? 'Não informado' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Número</label>
                                    <p>{{ $patient->dados_pessoais->numero ?? 'Não informado' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Código Postal</label>
                                    <p>{{ $patient->dados_pessoais->codigo_postal ?? 'Não informado' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Freguesia</label>
                                    <p>{{ $patient->dados_pessoais->freguesia ?? 'Não informado' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Concelho</label>
                                    <p>{{ $patient->dados_pessoais->concelho ?? 'Não informado' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Distrito</label>
                                    <p>{{ $patient->dados_pessoais->distrito ?? 'Não informado' }}</p>
                                </div>
                            </div>
                        </div>
                        
                    <div class="col-md-12 mb-3">
                        <h6 class="fw-bold">Informações médicas</h6> <!-- Informações Médicas -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Grupo Sanguíneo</label>
                            <p>{{ $patient->dados_pessoais->grupo_sanguineo ?? 'Não informado' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Peso (kg)</label>
                            <p>{{ $patient->dados_pessoais->peso ?? 'Não informado' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Altura (m)</label>
                            <p>{{ $patient->dados_pessoais->altura ?? 'Não informado' }}</p>
                        </div>
                    </div>
                </div>
                </div>
                <!-- Aba: Prontuário Médico -->
                <div class="tab-pane fade" id="modal-prontuario-medico" role="tabpanel">
                    <div class="prontuario-container">
                        <!-- Cabeçalho do Prontuário -->
                        <div class="prontuario-header mb-4">
                            <h5 class="section-title">
                                <i class="bx bx-file me-2"></i>Histórico de Prontuários
                            </h5>
                            <p class="text-muted small">
                                Histórico completo dos prontuários e relatórios médicos do paciente.
                            </p>
                        </div>

                        <!-- Lista de Relatórios -->
                        <div class="report-list bg-white p-3 rounded shadow-sm">
                            <div class="table-responsive">
                                <table class="table table-hover">
                            <thead>
                                <tr>
                                            <th style="width: 30%">Data e Hora</th>
                                            <th style="width: 40%">Médico</th>
                                            <th style="width: 30%">Ações</th>
                                </tr>
                            </thead>
                                    <tbody>
                                        <!-- Será preenchido via JavaScript -->
                                    </tbody>
                        </table>
                    </div>
                        </div>

                        <!-- Detalhes do Relatório (inicialmente oculto) -->
                    <div class="report-details mt-4" style="display: none;">
                            <div class="card border-0 bg-white shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="card-title mb-0">
                                            <i class="bx bx-detail me-2"></i>Detalhes do Relatório
                                        </h6>
                                        <button class="btn btn-sm btn-outline-secondary" id="backToList">
                                            <i class="bx bx-arrow-back me-1"></i>Voltar
                                        </button>
                                    </div>
                                    
                                    <div class="report-metadata mb-3">
                                        <span class="badge bg-primary me-2" id="reportDate"></span>
                                        <span class="text-muted" id="reportDoctor"></span>
                                    </div>
                                    
                                    <div class="report-content p-3 border rounded">
                                        <p id="reportContent" class="mb-0"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>             
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="salvarPaciente">Salvar</button>
        </div>
      </div>
    </div>
  </div>
  
<script>
// Gestão do modal para adicionar novo paciente
document.getElementById('openModal')?.addEventListener('click', () => {
    document.getElementById('novopacienteModal').style.display = 'block';  // Mostra o modal
});

document.getElementById('closeModal')?.addEventListener('click', () => {
    document.getElementById('novopacienteModal').style.display = 'none';   // Esconde o modal
});

// Função para visualizar o prontuário médico do utente
$(document).ready(function() {
    $('#addPacienteModal').on('show.bs.modal', function(event) {
        // Obtém o ID do utente selecionado
        const patientId = $(event.relatedTarget).data('patient-id');
        const prontuarioTab = $('#modal-prontuario-medico');
        const reportList = prontuarioTab.find('.report-list tbody');
        const reportDetails = prontuarioTab.find('.report-details');

        // Carrega os dados pessoais do utente através de uma chamada AJAX
        $.get(`/patient-details/${patientId}`, function(response) {
            if (response.success) {
                const dados = response.dados_pessoais;
                const patient = response.patient;

                // Atualiza o modal com os dados do utente
                $('#modal-dados-pessoais').html(`
                    <div class="row">
                        <!-- Informações Básicas -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nome Completo</label>
                            <p>${patient.name}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Data de Nascimento</label>
                            <p>${dados.data_nascimento || 'Não preenchido'}</p>
                        </div>
                        
                        <!-- Documentos -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">NIF</label>
                            <p>${dados.nif || 'Não preenchido'}</p>
                        </div>
                        
                        <!-- Informações Pessoais -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Sexo</label>
                            <p>${dados.sexo || 'Não preenchido'}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Estado Civil</label>
                            <p>${dados.estado_civil || 'Não preenchido'}</p>
                        </div>
                        
                        <!-- Endereço -->
                        <div class="col-md-12 mb-3">
                            <h6 class="fw-bold">Endereço</h6>
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Morada</label>
                                    <p>${dados.morada || 'Não preenchido'}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Número</label>
                                    <p>${dados.numero || 'Não preenchido'}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Código Postal</label>
                                    <p>${dados.codigo_postal || 'Não preenchido'}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Freguesia</label>
                                    <p>${dados.freguesia || 'Não preenchido'}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Concelho</label>
                                    <p>${dados.concelho || 'Não preenchido'}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Distrito</label>
                                    <p>${dados.distrito || 'Não preenchido'}</p>
                                </div>
                            </div>
                        </div>
                    <div class="col-md-12 mb-3">
                        <h6 class="fw-bold">Informações médicas</h6>
                        <!-- Informações Médicas -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Grupo Sanguíneo</label>
                            <p>${dados.grupo_sanguineo || 'Não preenchido'}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Peso (kg)</label>
                            <p>${dados.peso || 'Não preenchido'}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Altura (m)</label>
                            <p>${dados.altura || 'Não preenchido'}</p>
                        </div>
                    </div>
                </div>
                `);
            } else {
                // Mostra mensagem de erro se falhar
                console.error('Erro ao carregar dados:', response.message);
                alert('Erro ao carregar dados do utente');
            }
        }).fail(function(error) {
            // Tratamento de erro na chamada AJAX
            console.error('Erro na requisição:', error);
            alert('Erro ao carregar dados do utente');
        });

        // Carrega os relatórios médicos do utente
        $.get(`/patient-reports/${patientId}`, data => {
            reportList.empty();  // Limpa a lista atual
            if (data.length) {
                // Se existirem relatórios, mostra-os na tabela
                data.forEach(report => {
                    const date = new Date(report.report_date_time);
                    reportList.append(`
                        <tr>
                            <td>${date.toLocaleDateString('pt-PT')} ${date.toLocaleTimeString('pt-PT')}</td>
                            <td>${report.doctor_name || 'Não informado'}</td>
                            <td>
                                <button class="btn btn-sm btn-primary view-report" 
                                    data-content="${report.content}"
                                    data-date="${date.toLocaleDateString('pt-PT')}"
                                    data-doctor="${report.doctor_name}">
                                    Ver Relatório
                                </button>
                            </td>
                        </tr>
                    `);
                });
            } else {
                // Se não houver relatórios, mostra mensagem
                reportList.append('<tr><td colspan="3" class="text-center">Sem relatórios</td></tr>');
            }
        });

        // Gestão da visualização de relatórios individuais
        $(document).on('click', '.view-report', function() {
            // Mostra os detalhes do relatório selecionado
            $('#reportContent').text($(this).data('content'));
            $('#reportDate').text($(this).data('date'));
            $('#reportDoctor').text($(this).data('doctor'));
            $('.report-list').hide();
            reportDetails.show();
        });

        // Botão para voltar à lista de relatórios
        $('#backToList').on('click', () => {
            reportDetails.hide();
            $('.report-list').show();
        });
    });
});

// Função para eliminar um utente
document.querySelectorAll('.delete-patient')?.forEach(button => {
    button.addEventListener('click', function() {
        // Confirmação antes de eliminar
        if (confirm('Tem a certeza que pretende eliminar este utente?')) {
            // Chamada AJAX para eliminar o utente
            fetch(`/list/patients/${this.dataset.id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => {
                if (res.ok) {
                    // Remove a linha da tabela se eliminado com sucesso
                    this.closest('tr').remove();
                    alert('Utente eliminado com sucesso');
                }
            });
        }
    });
});

// Sistema de pesquisa de utentes em tempo real
document.addEventListener('DOMContentLoaded', function () {
    const patientSearch = document.getElementById('patientSearch');

    if (patientSearch) {
        patientSearch.addEventListener('input', function () {
            const searchQuery = this.value;

            fetch(`/list/patients?search=${encodeURIComponent(searchQuery)}`)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTableBody = doc.querySelector('#patientTableBody');
                    const newPagination = doc.querySelector('.pagination');

                    document.querySelector('#patientTableBody').innerHTML = newTableBody.innerHTML;
                    document.querySelector('.pagination').innerHTML = newPagination.innerHTML;
                })
                .catch(error => {
                    console.error('Erro ao buscar os utentes:', error);
                });
        });
    } else {
        console.error('Elemento com ID "patientSearch" não encontrado!');
    }
});
</script>
 

@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>