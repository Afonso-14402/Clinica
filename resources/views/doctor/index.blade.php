@extends('layouts.admin')

<style>
    .dashboard-container {
        padding: 2rem;
    }

    .welcome-section {
        background: linear-gradient(135deg, #0073e6 0%, #00428f 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .stats-card {
        background: white;
        border-radius: 15px;
        padding: 1.8rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .stats-icon {
        background: #e3f2fd;
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stats-icon i {
        font-size: 24px;
        color: #0073e6;
    }

    .stats-label {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stats-number {
        color: #0073e6;
        font-size: 2.2rem;
        font-weight: 600;
        margin: 0;
    }

    .next-appointment-card {
        background: #ffffff;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-left: 5px solid #0073e6;
    }

    .appointment-time {
        font-size: 2rem;
        font-weight: bold;
        color: #0073e6;
    }

    .appointment-details {
        margin-top: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .start-consultation-btn {
        background: #004a26;
        color: white;
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        border: none;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        margin-top: 1rem;
    }

    .start-consultation-btn:hover {
        background: #006633;
        transform: translateY(-2px);
        color: white;
    }

    .appointments-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .appointments-table th {
        background: #f8f9fa;
        padding: 1rem;
        font-weight: 600;
    }

    .appointments-table td {
        padding: 1rem;
        vertical-align: middle;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .pagination {
        margin-top: 2rem;
    }

    .page-link {
        color: #0073e6;
        border: none;
        padding: 0.5rem 1rem;
        margin: 0 0.2rem;
        border-radius: 5px;
    }

    .page-item.active .page-link {
        background: #0073e6;
        border-color: #0073e6;
    }

    /* Novos estilos para a tabela simplificada */
    .simple-table {
        width: 100%;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-top: 2rem;
    }

    .simple-table thead {
        background-color: #f8f9fa;
    }

    .simple-table th {
        color: #495057;
        font-weight: 600;
        padding: 1rem;
        text-align: left;
    }

    .simple-table td {
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
        color: #666;
    }

    .simple-table tr:last-child td {
        border-bottom: none;
    }

    .simple-table tr:hover {
        background-color: #f8f9fa;
    }

    .status-pill {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        display: inline-block;
    }

    .status-agendado { background-color: #e3f2fd; color: #1976d2; }
    .status-concluido { background-color: #e8f5e9; color: #2e7d32; }
    .status-cancelado { background-color: #ffebee; color: #c62828; }
    .status-andamento { background-color: #fff3e0; color: #f57c00; }
</style>

@section('content')

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
<div class="dashboard-container">
    
    <div>
        <h1>Olá, Dr. {{ $user->name }}</h1>
        <p class="dashboard-subtitle">Seu Painel</p>
        <style>
            .dashboard-subtitle {
                color: #6c757d;
                font-size: 1.1rem;
                margin-top: 0.5rem;
                padding-bottom: 1rem;
                border-bottom: 2px solid #e9ecef;
            }
        </style>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="next-appointment-card">
                <h3 class="mb-4"><i class="bx bx-calendar me-2"></i>Próximo Atendimento</h3>
                
                @if ($nextAppointment && \Carbon\Carbon::parse($nextAppointment->appointment_date_time)->isToday())
                    <div class="appointment-time">
                        {{ $tempoRestante->h }}h {{ $tempoRestante->i }}min
                    </div>
                    <div class="appointment-details">
                        <p><i class="bx bx-user me-2"></i><strong>Paciente:</strong> {{ $nextAppointment->patient->name }}</p>
                        <p><i class="bx bx-time me-2"></i><strong>Horário:</strong> 
                            {{ \Carbon\Carbon::parse($nextAppointment->appointment_date_time)->format('H:i') }}
                        </p>
                        <a href="{{ route('consulta.iniciar', ['id' => $nextAppointment->id]) }}" 
                           class="start-consultation-btn">
                            <i class="bx bx-play-circle me-2"></i>Iniciar Consulta
                        </a>
                    </div>
                @else
                    <div class="text-muted">
                        <i class="bx bx-calendar-x fs-1"></i>
                        <p class="mt-3">Nenhuma consulta agendada para hoje.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon">
                                <i class='bx bx-calendar-check' style="font-size: 24px;"></i>
                            </div>
                            <div class="ms-3">
                                <p class="stats-label mb-1">Consultas Hoje</p>
                                <h2 class="stats-number mb-0">{{ $consultasHoje }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <h3 class="mb-4">Minhas Consultas</h3>
    <div class="appointments-table mt-5">
        <div class="table-responsive">
            <table class="simple-table" style="border: 1px solid #e0e0e0; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <thead>
                    <tr style="border-bottom: 2px solid #f0f0f0; background-color: #fafafa;">
                        <th style="border-right: 1px solid #f0f0f0; padding: 1.3rem; font-weight: 600;">Data e Hora</th>
                        <th style="border-right: 1px solid #f0f0f0; padding: 1.3rem; font-weight: 600;">Paciente</th>
                        <th style="border-right: 1px solid #f0f0f0; padding: 1.3rem; font-weight: 600;">Especialidade</th>
                        <th style="padding: 1.3rem; font-weight: 600;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($custasdortor as $appointment)
                        <tr style="border-bottom: 1px solid #f5f5f5; transition: all 0.2s ease;">
                            <td style="border-right: 1px solid #f5f5f5; padding: 1.2rem; background-color: #ffffff;">
                                <i class='bx bx-calendar' style="color: #0073e6; margin-right: 8px;"></i>
                                {{ \Carbon\Carbon::parse($appointment->appointment_date_time)->format('d/m/Y H:i') }}
                            </td>
                            <td style="border-right: 1px solid #f5f5f5; padding: 1.2rem; background-color: #ffffff;">
                                <i class='bx bx-user' style="color: #666; margin-right: 8px;"></i>
                                {{ $appointment->patient ? $appointment->patient->name : 'Paciente não encontrado' }}
                            </td>
                            <td style="border-right: 1px solid #f5f5f5; padding: 1.2rem; background-color: #ffffff;">
                                <i class='bx bx-plus-medical' style="color: #666; margin-right: 8px;"></i>
                                {{ $appointment->specialty ? $appointment->specialty->name : 'Especialidade não encontrada' }}
                            </td>
                            <td style="padding: 1.2rem; background-color: #ffffff;">
                                <span class="status-pill status-{{ strtolower(str_replace(' ', '', $appointment->status->status)) }}" 
                                      style="box-shadow: 0 2px 4px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.05);">
                                    {{ $appointment->status->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center" style="padding: 2.5rem; background-color: #ffffff;">
                                <i class='bx bx-calendar-x' style="font-size: 2.2rem; color: #888"></i>
                                <p class="mt-3" style="color: #666;">Nenhuma consulta encontrada.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $custasdortor->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Função auxiliar para definir cores dos status
    function getStatusColor(status) {
        const colors = {
            'Agendado': 'info',
            'Concluído': 'success',
            'Cancelado': 'danger',
            'Em andamento': 'warning'
        };
        return colors[status] || 'secondary';
    }
</script>
@endpush

@endsection
