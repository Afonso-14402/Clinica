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
<div class="container">
   

    <div class="row mt-4">
        <!-- Próxima consulta -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4>Próxima Consulta</h4>
                    @if ($nextAppointment)
                        <p><strong>Data e Hora:</strong> {{ $nextAppointment->appointment_date_time }}</p>
                        <p><strong>Paciente:</strong> {{ $nextAppointment->patient->name }}</p>
                        <p><strong>Especialidade:</strong> {{ $nextAppointment->specialty->name }}</p>
                    @else
                        <p>Nenhuma consulta agendada.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4>Marcação de consulta </h4>
                    <p>Você podes pedir uma consulta com o seu medico de família.</p>
                <br>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#appointmentModal">
                        Agendar Consulta
                    </button>
            </div>
        </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentModalLabel">Agendar Consulta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('requestAppointment') }}">
                    @csrf

                    <!-- Campo de Paciente -->
                    <div class="mb-3">
                        <label for="patient_user_id" class="form-label">Paciente</label>
                        <input 
                            type="text" 
                            id="patient-name" 
                            class="form-control" 
                            value="{{ Auth::user()->name }}" 
                            readonly
                        />
                        <input 
                            type="hidden" 
                            name="patient_user_id" 
                            id="patient_user_id" 
                            value="{{ Auth::id() }}" 
                        />
                    </div>

                    <!-- Campo de Médico de Família -->
                    <div class="mb-3">
                        <label for="doctor_user_id" class="form-label">Médico de Família</label>
                        <input 
                            type="text" 
                            id="doctor-name" 
                            class="form-control" 
                            value="{{ Auth::user()->familyDoctor?->doctor?->name ?? 'Nenhum médico associado' }}" 
                            readonly
                        />
                        <input 
                            type="hidden" 
                            name="doctor_user_id" 
                            id="doctor_user_id" 
                            value="{{ Auth::user()->familyDoctor?->doctor?->id ?? '' }}" 
                        />
                    </div>

                    <div class="mb-3">
                        <label for="appointment_day" class="form-label">Dia</label>
                        <input 
                            type="date" 
                            class="form-control" 
                            name="appointment_day" 
                            id="appointment_day" 
                            required 
                            onchange="loadAvailableTimes()"
                        />
                    </div>
                    
                    <div class="mb-3">
                        <label for="appointment_time" class="form-label">Horário</label>
                        <select 
                            class="form-select" 
                            name="appointment_time" 
                            id="appointment_time" 
                            required
                        >
                            <option value="" disabled selected>Selecione um horário</option>
                        </select>
                    </div>
                    

                    <!-- Mensagem de Erro -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Botões -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Agendar Consulta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function loadAvailableTimes() {
    const appointmentDay = document.getElementById('appointment_day').value;
    const doctorId = document.getElementById('doctor_user_id').value;
    const timeSelect = document.getElementById('appointment_time');

    // Limpa as opções atuais
    timeSelect.innerHTML = '<option value="" disabled selected>Selecione um horário</option>';

    if (!appointmentDay || !doctorId) {
        return;
    }

    // Verifica se o médico atende no dia selecionado
    fetch(`/doctor/${doctorId}/check-availability?date=${appointmentDay}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (!data.available) {
                timeSelect.innerHTML = `<option value="" disabled selected>${data.message}</option>`;
                timeSelect.disabled = true;
                return;
            }
            // Se o médico atende neste dia, carrega os horários disponíveis
            loadTimes(doctorId, appointmentDay);
        })
        .catch(error => {
            console.error('Erro ao verificar disponibilidade:', error);
            timeSelect.innerHTML = '<option value="" disabled selected>Erro ao verificar disponibilidade</option>';
            timeSelect.disabled = true;
        });
}

function loadTimes(doctorId, appointmentDay) {
    const timeSelect = document.getElementById('appointment_time');
    timeSelect.disabled = false;

    fetch(`/available-times?doctor_id=${doctorId}&day=${appointmentDay}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao carregar horários');
            }
            return response.json();
        })
        .then(data => {
            if (data.length === 0) {
                timeSelect.innerHTML = '<option value="" disabled selected>Sem horários disponíveis</option>';
                timeSelect.disabled = true;
            } else {
                data.forEach(time => {
                    const option = document.createElement('option');
                    option.value = time;
                    option.textContent = time;
                    timeSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Erro ao carregar horários:', error);
            timeSelect.innerHTML = '<option value="" disabled selected>Erro ao carregar horários</option>';
            timeSelect.disabled = true;
        });
}

</script>


    <!-- Lista de consultas -->
    <div class="row mt-5">
        <div class="col-12">
            <h2>Minhas Consultas</h2>
            
            <!-- Filtros -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form action="{{ route('patient.index') }}" method="GET" class="d-flex gap-2">
                        <select name="filter_doctor" class="form-select">
                            <option value="">Todos os Médicos</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ request('filter_doctor') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->name }}
                                </option>
                            @endforeach
                        </select>
                        
                        <select name="filter_date" class="form-select">
                            <option value="">Todas as Datas</option>
                            <option value="today" {{ request('filter_date') == 'today' ? 'selected' : '' }}>Hoje</option>
                        </select>
                        
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                        @if(request('filter_doctor') || request('filter_date'))
                            <a href="{{ route('patient.index') }}" class="btn btn-secondary">Limpar Filtros</a>
                        @endif
                    </form>
                </div>
            </div>

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
                            <td>{{ $appointment->appointment_date_time }}</td>
                            <td>{{ $appointment->doctor->name }}</td>
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

@endsection
