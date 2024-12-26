
@extends('layouts.admin')

<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    h1 {
        font-weight: bold;
        color: #2c3e50;
    }
</style>


@section('content')

<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0   d-xl-none ">
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
                    <small class="text-muted">{{ $user->role->role}}</small>
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
    <div class="navbar-search-wrapper search-input-wrapper  d-none">
    <input type="text" class="form-control search-input container-xxl border-0" placeholder="Search..." aria-label="Search...">
    <i class="bx bx-x bx-md search-toggler cursor-pointer"></i>
    </div>
    
    
</nav>     


<div class="content-wrapper">
    <div class="container">
        <!-- Título -->
        <h1 class="mt-4 text-center">Painel Administrativo da Clínica</h1>

        <!-- Cartões Resumo -->
        <div class="row mt-5">
            <!-- Total de Usuários -->
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total de Usuários</h5>
                        <h4 id="totalUsers">500</h4>
                    </div>
                </div>
            </div>
            <!-- Total de Consultas -->
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total de Consultas</h5>
                        <h4 id="totalAppointments">150</h4>
                    </div>
                </div>
            </div>
            <!-- Total de Especialidades -->
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Especialidades</h5>
                        <h4 id="totalSpecialties">10</h4>
                    </div>
                </div>
            </div>
            <!-- Relatórios Gerados -->
            <div class="col-md-3 mb-3">
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Relatórios Gerados</h5>
                        <h4 id="totalReports">30</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="row mt-5">
            <!-- Gráfico de Consultas por Especialidade -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <canvas id="appointmentsBySpecialtyChart"></canvas>
                    </div>
                </div>
            </div>
            <!-- Gráfico de Status de Consultas -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <canvas id="appointmentsStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>



</script>

@endsection


