@extends('layouts.admin')

@section('content')
<style>
    .profile-img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ccc;
    }
    .timer {
        font-size: 1.5rem;
        text-align: center;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background-color: #f8f9fa;
    }
    .btn-group-custom {
        margin-top: 20px;
    }
    .details h5 {
        font-size: 1.25rem;
        font-weight: bold;
    }
    .details p {
        margin-bottom: 8px;
        font-size: 1rem;
    }
    .prescription-btn {
        background-color: #28a745;
        color: white;
        font-size: 1rem;
    }
    .finalize-btn {
        background-color: #dc3545;
        color: white;
    }
    .section-title {
        font-weight: bold;
        margin-top: 20px;
        margin-bottom: 10px;
        border-bottom: 2px solid #ddd;
        padding-bottom: 5px;
    }
    .stat-card {
        border-radius: 10px;
        color: white;
        padding: 20px;
        text-align: center;
        transition: transform 0.2s ease-in-out;
    }
    .stat-card:hover {
        transform: scale(1.05);
    }
    .stat-card .icon {
        font-size: 40px;
        margin-bottom: 10px;
    }
    .stat-card .number {
        font-size: 32px;
        font-weight: bold;
    }
    .stat-card .description {
        font-size: 16px;
    }
    .table-container {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 30px;
    }
    .table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
    .activity-container {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 30px;
    }
</style>

<div class="container-fluid">
    <!-- Título do Dashboard -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Administrativo</h1>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="row">
        <!-- Total de Utentes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100" style="background-color: #4e73df;">
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="number">{{ $totalPatients ?? '0' }}</div>
                <div class="description">Total de Utentes</div>
            </div>
        </div>

        <!-- Total de Médicos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100" style="background-color: #1cc88a;">
                <div class="icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="number">{{ $totalDoctors ?? '0' }}</div>
                <div class="description">Total de Médicos</div>
            </div>
        </div>

        <!-- Consultas Hoje -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100" style="background-color: #36b9cc;">
                <div class="icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="number">{{ $todayAppointments ?? '0' }}</div>
                <div class="description">Consultas Hoje</div>
            </div>
        </div>

        <!-- Consultas Pendentes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100" style="background-color: #f6c23e;">
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="number">{{ $pendingAppointments ?? '0' }}</div>
                <div class="description">Consultas Pendentes</div>
            </div>
        </div>
    </div>

    <!-- Log de Atividades -->
    <div class="row">
        <div class="col-12">
            <div class="activity-container">
                <h4 class="mb-4"><i class="fas fa-history"></i> Log de Atividades Recentes</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Data/Hora</th>
                                <th>Tipo</th>
                                <th>Descrição</th>
                                <th>Utilizador</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activityLogs ?? [] as $log)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    @switch($log->type)
                                        @case('appointment')
                                            <span class="badge badge-info">Consulta</span>
                                            @break
                                        @case('added')
                                            <span class="badge badge-success">Adição</span>
                                            @break
                                        @case('appointment_status_change')
                                            <span class="badge badge-warning">Alteração Status</span>
                                            @break
                                        @case('appointment_reschedule')
                                            <span class="badge badge-primary">Reagendamento</span>
                                            @break
                                        @case('toggle_status_patient')
                                            <span class="badge badge-danger">Alteração Estado do Utente</span>
                                            @break
                                        @default
                                            <span class="badge badge-secondary">{{ $log->type }}</span>
                                    @endswitch
                                </td>
                                <td>{{ $log->description }}</td>
                                <td>{{ $log->user->name ?? 'Sistema' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if(isset($activityLogs) && method_exists($activityLogs, 'links'))
                    <div class="mt-3">
                        {{ $activityLogs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
