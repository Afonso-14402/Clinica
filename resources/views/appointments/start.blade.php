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

    <!-- Título -->
    <h2 class="mb-4">Ficha de Anamnese</h2>

    <!-- Card Principal -->
    <div class="card">
        <div class="card-body d-flex align-items-center">
            <!-- Imagem do Paciente -->
            <div class="me-4">
                <img src="https://via.placeholder.com/120" alt="Foto do Paciente" class="rounded-circle" style="width: 120px; height: 120px;">
            </div>

            <!-- Informações do Paciente -->
            <div class="flex-grow-1">
                <h4 class="mb-1">{{ $appointment->patient->name }}</h4>
                <p><strong>Data e Hora:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date_time)->format('d/m/Y H:i') }}</p>
                <p><strong>Médico:</strong> {{ $appointment->doctor->name }}</p>
                @if($appointment->specialties_id)
                    <p><strong>Especialidade:</strong> {{ $appointment->specialty->name }}</p>
                @endif
    
            </div>
        </div>
    </div>

    <!-- Botões e Temporizador -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <a href= "{{ route('doctor.index') }}" class="btn btn-warning btn-sm">
       
            
            <i class="fas fa-arrow-left"></i> Retroceder
        </a>

        <div class="text-center">
            <div class="bg-light p-3 rounded" style="display: inline-block;">
                <i class="fas fa-clock text-primary"></i>
                <span class="fs-4">00:00:00</span>
            </div>
        </div>

        <a href="#" class="btn btn-success btn-sm">
            <i class="fas fa-prescription-bottle"></i> Prescrever Medicamento
        </a>
    </div>

    <!-- Formulário de Relatório Médico -->
    <div class="card mt-4">
        <div class="card-body">
            <h4>Preencher Relatório Médico</h4>
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('consulta.salvarRelatorio', $appointment->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="content" class="form-label">Relatório</label>
                    <textarea 
                        class="form-control @error('content') is-invalid @enderror" 
                        id="content" 
                        name="content" 
                        rows="6" 
                        placeholder="Descreva aqui o diagnóstico, tratamento ou observações relevantes..." 
                        aria-label="Campo para preenchimento do relatório médico" 
                        required>{{ old('content', $appointment->report->content ?? '') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success mt-3">Finalizar consulta</button>
            </form>
        </div>
    </div>
</div>
@endsection
