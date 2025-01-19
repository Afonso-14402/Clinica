@extends('layouts.admin')

<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .dashboard-card {
        border-radius: 12px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        background-color: #f5f8ff;
        padding: 25px;
        text-align: center;
        font-family: Arial, sans-serif;
        max-width: 600px;
        margin: 20px auto;
    }

    .dashboard-card h1 {
        font-size: 3.2rem;
        font-weight: bold;
        color: #0073e5;
        margin-bottom: 15px;
        letter-spacing: 1px;
    }

    .dashboard-card h2 {
        font-size: 1.5rem;
        color: #333333;
        margin-bottom: 10px;
    }

    .dashboard-card p {
        font-size: 1.1rem;
        color: #555555;
        line-height: 1.6;
    }

    .dashboard-card p strong {
        font-weight: bold;
        color: #0073e5;
    }

    .dashboard-card .details-button {
        margin-top: 20px;
        padding: 10px 25px;
        font-size: 1rem;
        background-color: #004a26;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .dashboard-card .details-button:hover {
        background-color: #007a35;
    }
</style>

@section('content')

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

<div class="container">
    <h1>Bem-vindo, {{ $user->name }}</h1>
    <div class="container text-center">
        <div class="row align-items-start">
          <div class="col-6">

            <div class="dashboard-card" style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 24px; background-color: #fff; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); width: 400px;">

                @if ($nextAppointment)
                    <p style="font-size: 16px; margin: 0; color: #666; text-align: left;">Próximo Atendimento inicia-se em</p>
                    <p style="font-size: 18px; font-weight: bold; margin: 12px 0; text-align: left;">{{ $tempoRestante->h }} horas e {{ $tempoRestante->i }} minutos</p>
                    <p style="font-size: 16px; color: #333; margin: 0; text-align: left;"><strong>Paciente:</strong> {{ $nextAppointment->patient->name }}</p>
                    <a href="{{ route('consulta.iniciar', ['id' => $nextAppointment->id]) }}" style="display: inline-block; margin-top: 16px; padding: 12px 20px; text-decoration: none; color: #fff; background-color: #6c63ff; border-radius: 6px; font-size: 16px; text-align: left;">Iniciar Consulta</a>
                @else
                    <p style="font-size: 16px; margin: 0; color: #666; text-align: left;">Nenhuma consulta agendada no momento.</p>
                    <span style="display: inline-block; margin-top: 16px; padding: 12px 20px; text-decoration: none; color: #aaa; background-color: #f0f0f0; border-radius: 6px; font-size: 16px; text-align: left;">Sem consultas disponíveis</span>
                @endif
            
            </div>
            
            
          </div>
          <div class="col-6">
            
          </div>
        </div>
      </div>
    
    

    <!-- Lista de consultas -->
    <div class="row mt-5">
        <div class="col-12">
            <h2>Minhas Consultas</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Data e Hora</th>
                        <th>Paciente</th>
                        <th>Especialidade</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($custasdortor as $appointment)
                        <tr>
                            <td>{{ $appointment->appointment_date_time }}</td>
                            <td>{{ $appointment->patient->name }}</td>
                            <td>{{ $appointment->specialty->name }}</td>
                            <td>{{ $appointment->status->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Nenhuma consulta encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <nav>
                <ul class="pagination justify-content-center">
                    {{-- Link para a página anterior --}}
                    @if ($custasdortor->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">Anterior</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $custasdortor->previousPageUrl() }}">Anterior</a>
                        </li>
                    @endif
                
                    {{-- Links para as páginas --}}
                    @for ($i = 1; $i <= $custasdortor->lastPage(); $i++)
                        <li class="page-item {{ $custasdortor->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $custasdortor->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                
                    {{-- Link para a próxima página --}}
                    @if ($custasdortor->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $custasdortor->nextPageUrl() }}">Próxima</a>
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

@endsection
