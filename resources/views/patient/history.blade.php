@extends('layouts.admin')

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

<br>
<br>
<div class="container">

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('appointments.history') }}" method="GET" class="row g-3">
                <!-- Filtro de Período -->
                <div class="col-md-3">
                    <label class="form-label">Período</label>
                    <select name="date_filter" class="form-select" onchange="toggleCustomDates(this.value)">
                        <option value="">Todos os períodos</option>
                        <option value="today" {{ request('date_filter') === 'today' ? 'selected' : '' }}>Hoje</option>
                        <option value="week" {{ request('date_filter') === 'week' ? 'selected' : '' }}>Esta Semana</option>
                        <option value="month" {{ request('date_filter') === 'month' ? 'selected' : '' }}>Este Mês</option>
                        <option value="custom" {{ request('date_filter') === 'custom' ? 'selected' : '' }}>Personalizado</option>
                    </select>
                </div>

                <!-- Datas Personalizadas -->
                <div class="col-md-3 custom-dates" style="display: none;">
                    <label class="form-label">Data Inicial</label>
                    <input type="date" name="date_start" class="form-control" value="{{ request('date_start') }}">
                </div>
                <div class="col-md-3 custom-dates" style="display: none;">
                    <label class="form-label">Data Final</label>
                    <input type="date" name="date_end" class="form-control" value="{{ request('date_end') }}">
                </div>
                @if (isset($user) && $user->role->role != 'Doctor')
                <!-- Filtro de Médico -->
                <div class="col-md-3">
                    <label class="form-label">Médico</label>
                    <select name="doctor_id" class="form-select">
                        <option value="">Todos os médicos</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro de Especialidade -->
                <div class="col-md-3">
                    <label class="form-label">Especialidade</label>
                    <select name="specialty_id" class="form-select">
                        <option value="">Todas as especialidades</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}" {{ request('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                {{ $specialty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <!-- Filtro de Status -->
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status_id" class="form-select">
                        <option value="">Todos os status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>
                                {{ $status->status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro de Período (Passado/Futuro) -->
                <div class="col-md-3">
                    <label class="form-label">Período</label>
                    <select name="time_period" class="form-select">
                        <option value="">Todos</option>
                        <option value="past" {{ request('time_period') === 'past' ? 'selected' : '' }}>Consultas Passadas</option>
                        <option value="future" {{ request('time_period') === 'future' ? 'selected' : '' }}>Consultas Futuras</option>
                    </select>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    @if(request()->hasAny(['date_filter', 'doctor_id', 'specialty_id', 'status_id', 'time_period']))
                        <a href="{{ route('appointments.history') }}" class="btn btn-secondary">Limpar Filtros</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Consultas -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Data e Hora</th>
                            <th>Médico</th>
                            <th>Especialidade</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($appointments as $appointment)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date_time)->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($appointment->doctor_user_id === auth()->id())
                                        {{ $appointment->patient->name }} (Paciente)
                                    @else
                                        {{ $appointment->doctor->name }} (Médico)
                                    @endif
                                </td>
                                <td>{{ $appointment->specialty->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $appointment->status->id == 1 ? 'warning' : 
                                                          ($appointment->status->id == 2 ? 'success' : 
                                                          ($appointment->status->id == 3 ? 'danger' : 'info')) }}">
                                        {{ $appointment->status->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Nenhuma consulta encontrada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination justify-content-center">
                        @if ($appointments->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Anterior</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $appointments->previousPageUrl() }}">Anterior</a>
                            </li>
                        @endif

                        @for ($i = 1; $i <= $appointments->lastPage(); $i++)
                            <li class="page-item {{ $appointments->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ $appointments->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        @if ($appointments->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $appointments->nextPageUrl() }}">Próxima</a>
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
</div>

@endsection

@section('scripts')
<script>
function toggleCustomDates(value) {
    const customDates = document.querySelectorAll('.custom-dates');
    customDates.forEach(element => {
        element.style.display = value === 'custom' ? 'block' : 'none';
    });
}

// Inicializar o estado dos campos de data personalizada
document.addEventListener('DOMContentLoaded', function() {
    const dateFilter = document.querySelector('select[name="date_filter"]').value;
    toggleCustomDates(dateFilter);
});
</script>
 