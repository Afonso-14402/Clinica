@extends('layouts.admin')
@section('content')
<style>
    .stat-card {
        border-radius: 10px;
        color: white;
        padding: 20px;
        text-align: center;
        transition: transform 0.2s ease-in-out;
        margin-bottom: 20px;
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
    .stat-card .more-info {
        margin-top: 10px;
        display: block;
        color: white;
        text-decoration: none;
    }
    .stat-card .more-info:hover {
        text-decoration: underline;
    }
    .chart-container {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }
    .table-container {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }
    .table thead {
        background: #f8f9fa;
    }
    .badge {
        padding: 8px 12px;
        font-size: 12px;
    }
    
    /* Estilos simplificados para a tabela de logs */
    .activity-table {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .activity-table thead {
        background: #f8f9fc;
        border-bottom: 2px solid #e3e6f0;
    }
    
    .activity-table th {
        color: #4e73df;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        padding: 15px;
        border: none;
    }
    
    .activity-table tbody tr {
        border-bottom: 1px solid #f1f1f1;
        transition: all 0.2s ease;
    }
    
    .activity-table tbody tr:hover {
        background-color: #f8f9fc;
    }
    
    .activity-table td {
        padding: 12px 15px;
        vertical-align: middle;
    }
    
    .activity-badge {
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
        display: inline-block;
        min-width: 100px;
        text-align: center;
    }
    
    .activity-badge i {
        margin-right: 5px;
    }
    
    .activity-badge.type-consulta {
        background: #e3f2fd;
        color: #1976d2;
    }
    
    .activity-badge.type-adicao {
        background: #e8f5e9;
        color: #2e7d32;
    }
    
    .activity-badge.type-status {
        background: #fff3e0;
        color: #f57c00;
    }
    
    .activity-badge.type-reagendamento {
        background: #f3e5f5;
        color: #7b1fa2;
    }
    
    .activity-badge.type-utente {
        background: #ffebee;
        color: #c62828;
    }
    
    .activity-badge.type-default {
        background: #f5f5f5;
        color: #616161;
    }
    
    .activity-badge.date {
        background: #f8f9fc;
        color: #5a5c69;
        border: 1px solid #e3e6f0;
        font-family: monospace;
        min-width: 130px;
    }
    
    .activity-badge.user {
        background: #e8eaf6;
        color: #3f51b5;
    }
    
    .activity-badge.system {
        background: #eeeeee;
        color: #757575;
    }
    
    .activity-description {
        color: #5a5c69;
        font-size: 0.9rem;
    }
</style>
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

<br>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dashboard Administrativo</h1>

    <!-- Cards de Estatísticas -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-primary">
                <div class="icon"><i class="fas fa-user-md"></i></div>
                <div class="number">{{ $totalMedicos }}</div>
                <div class="description">Médicos Ativos</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-success">
                <div class="icon"><i class="fas fa-users"></i></div>
                <div class="number">{{ $totalPacientes }}</div>
                <div class="description">Utentes Registados</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-info">
                <div class="icon"><i class="fas fa-calendar-check"></i></div>
                <div class="number">{{ $totalConsultasRealizadas }}</div>
                <div class="description">Consultas Realizadas</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-warning">
                <div class="icon"><i class="fas fa-stethoscope"></i></div>
                <div class="number">{{ $totalEspecialidades }}</div>
                <div class="description">Especialidades</div>
            </div>
        </div>
    </div>

    <!-- Estatísticas Adicionais -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Consultas Hoje</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $consultasHoje }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Consultas Pendentes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $consultasPendentes }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Consultas Canceladas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $consultasCanceladas }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Consultas Recentes -->
    <div class="row">
        <div class="col-12">
            <div class="table-container">
                <h6 class="m-0 font-weight-bold text-primary mb-3">
                    <i class="fas fa-list mr-2"></i>Consultas Recentes
                </h6>
                <div class="table-responsive">
                    <table class="table table-hover">
                <thead>
                    <tr>
                                <th>
                                    <i class="fas fa-user mr-1"></i>Utente
                                </th>
                                <th>
                                    <i class="fas fa-user-md mr-1"></i>Médico
                                </th>
                                <th>
                                    <i class="fas fa-calendar-alt mr-1"></i>Data
                                </th>
                                <th>
                                    <i class="fas fa-stethoscope mr-1"></i>Especialidade
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-info-circle mr-1"></i>Status
                                </th>
                    </tr>
                </thead>
                <tbody>
                            @foreach($consultasRecentes as $consulta)
                            <tr>
                                <td>
                                    <span class="font-weight-medium">{{ $consulta['paciente'] }}</span>
                                </td>
                                <td class="text-primary">{{ $consulta['medico'] }}</td>
                                <td>
                                    <span class="activity-badge date">{{ $consulta['data'] }}</span>
                                </td>
                                <td>{{ $consulta['especialidade'] }}</td>
                                <td class="text-center">
                                    @switch($consulta['status']['label'])
                                        @case('Agendada')
                                            <span class="activity-badge type-consulta">
                                                <i class="fas fa-calendar-check"></i>Agendada
                                            </span>
                                            @break
                                        @case('Concluída')
                                            <span class="activity-badge type-adicao">
                                                <i class="fas fa-check-circle"></i>Concluída
                                            </span>
                                            @break
                                        @case('Cancelada')
                                            <span class="activity-badge type-utente">
                                                <i class="fas fa-times-circle"></i>Cancelada
                                            </span>
                                            @break
                                        @case('Pendente')
                                            <span class="activity-badge type-status">
                                                <i class="fas fa-clock"></i>Pendente
                                            </span>
                                            @break
                                        @default
                                            <span class="activity-badge type-default">
                                                <i class="fas fa-info-circle"></i>{{ $consulta['status']['label'] }}
                                            </span>
                                    @endswitch
                                </td>
                    </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Log de Atividades -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center bg-gradient-primary">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-history mr-2"></i>Log de Atividades do Sistema
            </h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table activity-table">
                    <thead>
                        <tr>
                            <th class="text-center">
                                <i class="fas fa-clock"></i>
                                Data/Hora
                            </th>
                            <th class="text-center">
                                <i class="fas fa-tag"></i>
                                Tipo
                            </th>
                            <th>
                                <i class="fas fa-info-circle"></i>
                                Descrição
                            </th>
                            <th class="text-center">
                                <i class="fas fa-user"></i>
                                ....
                            </th>
                        </tr>
                    </thead>
                    <tbody id="logsTableBody">
                        @foreach($activityLogs as $log)
                        <tr>
                            <td class="text-center">
                                <span class="activity-badge date">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}
                                </span>
                            </td>
                            <td class="text-center">
                                @switch($log->type)
                                    @case('appointment')
                                        <span class="activity-badge type-consulta">
                                            <i class="fas fa-calendar-check"></i>Consulta
                                        </span>
                                        @break
                                    @case('added')
                                        <span class="activity-badge type-adicao">
                                            <i class="fas fa-plus-circle"></i>Adição
                                        </span>
                                        @break
                                    @case('appointment_status_change')
                                        <span class="activity-badge type-status">
                                            <i class="fas fa-exchange-alt"></i>Status
                                        </span>
                                        @break
                                    @case('appointment_reschedule')
                                        <span class="activity-badge type-reagendamento">
                                            <i class="fas fa-clock"></i>Reagendamento
                                        </span>
                                        @break
                                    @case('toggle_status_patient')
                                        <span class="activity-badge type-utente">
                                            <i class="fas fa-user-times"></i>Utente
                                        </span>
                                        @break
                                    @default
                                        <span class="activity-badge type-default">
                                            <i class="fas fa-info-circle"></i>{{ $log->type }}
                                        </span>
                                @endswitch
                            </td>
                            <td class="activity-description">{{ $log->description }}</td>
                            <td class="text-center">
                                @if($log->user_name)
                                    <span class="activity-badge user">
                                        <i class="fas fa-user mr-1"></i>{{ $log->user_name }}
                                    </span>
                                @else
                                    <span class="activity-badge system">
                                        <i class="fas fa-robot"></i>Sistema
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <div class="mt-4 pagination-container">
                @if ($activityLogs->hasPages())
                <nav>
                    <ul class="pagination pagination-sm justify-content-center">
                        {{-- Link Anterior --}}
                        @if ($activityLogs->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link shadow-sm">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link shadow-sm" href="{{ $activityLogs->previousPageUrl() }}">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        @endif

                        {{-- Links Numéricos --}}
                        @php
                            $start = max($activityLogs->currentPage() - 2, 1);
                            $end = min($start + 4, $activityLogs->lastPage());
                            $start = max(min($end - 4, $start), 1);
                        @endphp

                        @if($start > 1)
                            <li class="page-item">
                                <a class="page-link shadow-sm" href="{{ $activityLogs->url(1) }}">1</a>
                            </li>
                            @if($start > 2)
                                <li class="page-item disabled">
                                    <span class="page-link shadow-sm">...</span>
                                </li>
                            @endif
                        @endif

                        @for ($i = $start; $i <= $end; $i++)
                            <li class="page-item {{ $activityLogs->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link shadow-sm" href="{{ $activityLogs->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        @if($end < $activityLogs->lastPage())
                            @if($end < $activityLogs->lastPage() - 1)
                                <li class="page-item disabled">
                                    <span class="page-link shadow-sm">...</span>
                                </li>
                            @endif
                            <li class="page-item">
                                <a class="page-link shadow-sm" href="{{ $activityLogs->url($activityLogs->lastPage()) }}">
                                    {{ $activityLogs->lastPage() }}
                                </a>
                            </li>
                        @endif

                        {{-- Link Próximo --}}
                        @if ($activityLogs->hasMorePages())
                            <li class="page-item">
                                <a class="page-link shadow-sm" href="{{ $activityLogs->nextPageUrl() }}">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link shadow-sm">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            </li>
                        @endif
                    </ul>
                </nav>

                <div class="text-center text-muted small mt-2">
                    Mostrando {{ $activityLogs->firstItem() ?? 0 }} até {{ $activityLogs->lastItem() ?? 0 }} 
                    de {{ $activityLogs->total() }} registros
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script>
$(document).ready(function() {
    // Configuração do Toastr
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };

    // Função para carregar logs
    function loadLogs(url) {
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'html',
            beforeSend: function() {
                $('#logsTableBody').addClass('loading').fadeTo(300, 0.5);
                $('.pagination-container').fadeTo(300, 0.5);
            },
            success: function(response) {
                var tempDiv = $('<div>').html(response);
                $('#logsTableBody').html(tempDiv.find('#logsTableBody').html());
                $('.pagination-container').html(tempDiv.find('.pagination-container').html());
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar logs:', error);
                toastr.error('Erro ao carregar logs. Por favor, tente novamente.');
            },
            complete: function() {
                $('#logsTableBody').removeClass('loading').fadeTo(300, 1);
                $('.pagination-container').fadeTo(300, 1);
            }
        });
    }

    // Delegação de eventos para links de paginação
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        if (url) {
            loadLogs(url);
            window.history.pushState("", "", url);
        }
        return false;
    });
});
</script>
@endpush

@endsection
