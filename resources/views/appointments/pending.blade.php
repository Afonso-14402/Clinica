


@extends('layouts.admin')



@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

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
    <div class="card">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            
            Pedidos Pendentes
            
            <div class="col-md-4">
                <input type="text" id="doctorSearch" class="form-control" placeholder="Pesquisar por nome...">
            </div>

            <button id="exportButton" class="btn btn-success btn-sm">
                <i class="bx bx-download"></i> Exportar para Excel
            </button>  
            
        </h5>
        <div class="table-responsive text-nowrap" id="doctorTable">
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>Médico</th>
                        <th>Data e Hora</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->id }}</td>
                        <td>{{ $appointment->patient->name ?? 'N/A' }}</td>
                        <td>{{ $appointment->doctor->name ?? 'N/A' }}</td>
                        <td class="appointment-date-time">
                            {{ \Carbon\Carbon::parse($appointment->appointment_date_time)->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <span class="badge bg-warning">Pendente</span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Ações
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        @if ($appointment->status_id == 4) <!-- Se o status for Pendente -->
                                        <form action="{{ route('appointments.updateStatus', $appointment->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success">Aprovar</button>
                                        </form>
                                        @endif
                                    </li>
                                    <li>
                                        
                                        <form action="{{ route('appointments.updateStatus', $appointment->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="3">
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bx bx-x-circle me-1"></i> Rejeitar
                                            </button>
                                        </form>
                                        
                                    </li>
                                    <li>
                                        <button class="dropdown-item text-info" data-bs-toggle="modal" data-bs-target="#scheduleModal" data-doctor-id="{{ $appointment->doctor_user_id }}">
                                            <i class="bx bx-calendar me-1"></i> Ver Horário
                                        </button>
                                    </li>

                                    <li>
                                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#doctorAppointmentsModal" onclick="loadDoctorAppointments({{ $appointment->doctor_user_id }}, '{{ \Carbon\Carbon::parse($appointment->appointment_date_time)->toDateString() }}')">
                                            Ver Marcações do Dia
                                        </button>
                                    </li>
                                    
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Nenhum pedido pendente encontrado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <nav>
                
            </nav>
        </div>
    </div>
</div>

<!-- Modal para Ver Horário -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleModalLabel">Horário do Médico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Dia da Semana</th>
                            <th>Horário de Início</th>
                            <th>Horário de Término</th>
                        </tr>
                    </thead>
                    <tbody id="scheduleTableBody">
                        <!-- Dados serão preenchidos via JavaScript -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal para Marcações do Médico -->
<div class="modal fade" id="doctorAppointmentsModal" tabindex="-1" aria-labelledby="doctorAppointmentsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="doctorAppointmentsModalLabel">Marcações do Médico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="doctorAppointmentsList" class="list-group">
                    <li class="list-group-item text-center">Carregando...</li>
                </ul>                
            </div>
        </div>
    </div>
</div>



<script>

document.addEventListener('DOMContentLoaded', function () {
    function loadDoctorAppointments(doctorId, date) {
        const appointmentsList = document.getElementById('doctorAppointmentsList');

        if (!appointmentsList) {
            console.error('Elemento doctorAppointmentsList não encontrado.');
            return;
        }

        // Limpar lista e exibir mensagem de carregamento
        appointmentsList.innerHTML = '<li class="list-group-item text-center">Carregando...</li>';

        // Buscar marcações do backend
        fetch(`/doctor/appointments?doctor_id=${doctorId}&date=${date}`)
            .then(response => response.json())
            .then(data => {
                // Limpar a lista
                appointmentsList.innerHTML = '';

                if (data.length > 0) {
                    // Exibir cada marcação
                    data.forEach(appointment => {
                        const listItem = document.createElement('li');
                        listItem.className = 'list-group-item';

                        listItem.innerHTML = `
                            <strong>Paciente:</strong> ${appointment.patient.name}<br>
                            <strong>Horário:</strong> ${appointment.appointment_date_time}<br>
                            <strong>Especialidade:</strong> ${appointment.specialty.name || 'Não informado'}
                        `;
                        appointmentsList.appendChild(listItem);
                    });
                } else {
                    // Caso não haja marcações
                    appointmentsList.innerHTML = '<li class="list-group-item text-center">Nenhuma marcação para este dia.</li>';
                }
            })
            .catch(error => {
                // Tratar erros
                console.error('Erro ao carregar marcações:', error);
                appointmentsList.innerHTML = '<li class="list-group-item text-center text-danger">Erro ao carregar marcações.</li>';
            });
    }

    // Disponibiliza a função globalmente (opcional)
    window.loadDoctorAppointments = loadDoctorAppointments;
});




    // Buscar e exibir horários do médico
    document.querySelectorAll('[data-bs-target="#scheduleModal"]').forEach(button => {
        button.addEventListener('click', function () {
            const doctorId = this.getAttribute('data-doctor-id');
            
            axios.get(`/doctors/${doctorId}/schedule`)
                .then(response => {
                    const scheduleTableBody = document.getElementById('scheduleTableBody');
                    scheduleTableBody.innerHTML = ''; // Limpa os dados antigos

                    if (response.data.length === 0) {
                        scheduleTableBody.innerHTML = '<tr><td colspan="3" class="text-center">Nenhum horário encontrado.</td></tr>';
                    } else {
                        response.data.forEach(schedule => {
                            const row = `
                                <tr>
                                    <td>${schedule.day_of_week}</td>
                                    <td>${schedule.start_time}</td>
                                    <td>${schedule.end_time}</td>
                                </tr>
                            `;
                            scheduleTableBody.insertAdjacentHTML('beforeend', row);
                        });
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Erro ao carregar o horário do médico.');
                });
        });
    });


    function loadDoctorAppointments(doctorId, date) {
    const doctorAppointmentsContent = document.getElementById('doctorAppointmentsContent');
    doctorAppointmentsContent.innerHTML = '<p class="text-center text-muted">Carregando...</p>';

    axios.get(`/doctors/${doctorId}/appointments`, {
        params: { date }
    })
    .then(response => {
        if (response.data.length === 0) {
            doctorAppointmentsContent.innerHTML = '<p class="text-center text-muted">Nenhuma marcação encontrada para esta data.</p>';
        } else {
            const list = response.data.map(appointment => `
                <div class="mb-3">
                    <p><strong>Paciente:</strong> ${appointment.patient?.name ?? 'N/A'}</p>
                    <p><strong>Horário:</strong> ${new Date(appointment.appointment_date_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</p>
                    <hr>
                </div>
            `).join('');
            doctorAppointmentsContent.innerHTML = list;
        }
    })
    .catch(error => {
        console.error(error);
        doctorAppointmentsContent.innerHTML = '<p class="text-center text-danger">Erro ao carregar as marcações.</p>';
    });
}



</script>
@endsection