@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
@vite(['resources/css/app.css'])

<div class="container py-5">
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

    <!-- Dashboard Content -->
    <div class="content-wrapper">
        <div class="container">
            <!-- Title -->
            <h1 class="text-center my-4">Painel Administrativo da Clínica</h1>
            <!-- Cards Section -->
            <div class="row g-4">
                @php
                $cards = [
                    ['title' => 'Total de Usuários', 'value' => $totalUsers, 'icon' => 'bx bx-user', 'color' => 'primary'],
                    ['title' => 'Total de Pacientes', 'value' => $totalPatients, 'icon' => 'bx bx-user-circle', 'color' => 'info'],
                    ['title' => 'Total de Médicos', 'value' => $totalDoctors, 'icon' => 'bx bx-health', 'color' => 'success'],
                    ['title' => 'Consultas Pendentes', 'value' => $pendingAppointments, 'icon' => 'bx bx-time', 'color' => 'warning'],
                    ['title' => 'Total de Consultas', 'value' => $totalAppointments, 'icon' => 'bx bx-calendar', 'color' => 'danger'],
                    ['title' => 'Especialidades', 'value' => $totalSpecialties, 'icon' => 'bx bx-list-ul', 'color' => 'secondary'],
                    ['title' => 'Relatórios Gerados', 'value' => $totalReports, 'icon' => 'bx bx-bar-chart-alt', 'color' => 'dark']
                ];
                @endphp

                @foreach ($cards as $card)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="icon-box mb-3">
                                <i class="{{ $card['icon'] }} bx-lg text-{{ $card['color'] }}"></i>
                            </div>
                            <h5 class="card-title fw-semibold">{{ $card['title'] }}</h5>
                            <h2 class="fw-bold text-{{ $card['color'] }}">{{ $card['value'] }}</h2>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="container">
        <h1>Atividades Recentes</h1>
        <ul id="activity-list" class="list-group"></ul>
        <div id="pagination-controls" class="mt-3 d-flex justify-content-center"></div>
    </div>
    
</div>

@vite(['resources/vendor/js/app.js', 'resources/js/app.js','resources/vendor/libs/app.js'])
@endsection  

