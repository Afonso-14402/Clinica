@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                <i class="bx bx-menu bx-md"></i>
            </a>
        </div>
        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Notification -->
                <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        <span class="position-relative">
                            <i class="bx bx-bell bx-md"></i>
                            <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
                        </span>
                    </a>
                </li>
                <!--/ Notification -->
                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}" alt="Avatar do usuário" class="avatar">
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}" alt="Avatar do usuário" class="avatar">
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                        <small class="text-muted">{{ $user->role->role }}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider my-1"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bx bx-user bx-md me-3"></i><span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('settings.index') }}">
                                <i class="bx bx-cog bx-md me-3"></i><span>Settings</span>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider my-1"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('login.destroy') }}">
                                <i class="bx bx-power-off bx-md me-3"></i><span>Log Out</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!--/ User -->
            </ul>
        </div>
    
        <!-- Search Small Screens -->
        <div class="navbar-search-wrapper search-input-wrapper d-none">
            <input type="text" class="form-control search-input container-xxl border-0" placeholder="Search..." aria-label="Search...">
            <i class="bx bx-x bx-md search-toggler cursor-pointer"></i>
        </div>
    </nav>

    <div class="container py-5">
        <!-- Título -->
        <h2 class="mb-4 text-center text-primary">Ficha de Anamnese</h2>
    
        <!-- Card Principal -->
        <div class="card shadow-lg">
            <div class="card-body d-flex flex-column flex-md-row align-items-center">
                <!-- Imagem do Paciente -->
                <div class="text-center me-md-4 mb-4 mb-md-0">
                    <img src="https://via.placeholder.com/120" alt="Foto do Paciente" class="rounded-circle border border-primary" style="width: 120px; height: 120px;">
                </div>
    
                <!-- Informações do Paciente -->
                <div class="flex-grow-1">
                    <h4 class="text-dark mb-2">{{ $appointment->patient->name }}</h4>
                    <p><strong>Data e Hora:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date_time)->format('d/m/Y H:i') }}</p>
                    <p><strong>Médico:</strong> {{ $appointment->doctor->name }}</p>
                    @if($appointment->specialties_id)
                        <p><strong>Especialidade:</strong> {{ $appointment->specialty->name }}</p>
                    @endif
                </div>
            </div>
        </div>
    
        <!-- Formulário de Relatório Médico -->
        <div class="card mt-4 shadow-lg">
            <div class="card-body">
                <h4 class="text-primary mb-4">Preencher Relatório Médico</h4>
    
                <!-- Formulário -->
                <form action="{{ route('consulta.salvarRelatorio', $appointment->id) }}" method="POST">
                    @csrf
    
                    <!-- Seção 1: O que o paciente sente -->
                    <div class="mb-4">
                        <label for="sintomas" class="form-label">O que o paciente sente?</label>
                        <textarea 
                            class="form-control" 
                            id="sintomas" 
                            name="sintomas" 
                            rows="3" 
                            placeholder="Descreva aqui os sintomas relatados pelo paciente..." 
                            required></textarea>
                    </div>
    
                    <!-- Seção 2: Diagnóstico -->
                    <div class="mb-4">
                        <label for="diagnostico" class="form-label">Diagnóstico</label>
                        <textarea 
                            class="form-control" 
                            id="diagnostico" 
                            name="diagnostico" 
                            rows="3" 
                            placeholder="Informe o diagnóstico do paciente..." 
                            required></textarea>
                    </div>
    
                    <!-- Seção 3: Tratamento -->
                    <div class="mb-4">
                        <label for="tratamento" class="form-label">Tratamento</label>
                        <textarea 
                            class="form-control" 
                            id="tratamento" 
                            name="tratamento" 
                            rows="3" 
                            placeholder="Detalhe o tratamento recomendado..." 
                            required></textarea>
                    </div>
    
                    <!-- Seção 4: Observações -->
                    <div class="mb-4">
                        <label for="observacoes" class="form-label">Observações</label>
                        <textarea 
                            class="form-control" 
                            id="observacoes" 
                            name="observacoes" 
                            rows="3" 
                            placeholder="Insira aqui quaisquer observações adicionais..." 
                            required></textarea>
                    </div>
    
                    <!-- Botão de Envio -->
                    <button type="submit" class="btn btn-success w-100">Finalizar consulta</button>
                </form>
            </div>
        </div>
    </div>
@endsection
