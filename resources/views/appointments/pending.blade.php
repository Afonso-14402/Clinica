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
                                        <button class="dropdown-item text-success" data-bs-toggle="modal" 
                                            data-bs-target="#approveModal" 
                                            data-appointment-id="{{ $appointment->id }}">
                                            <i class="bx bx-check me-1"></i> Aprovar
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item text-warning" data-bs-toggle="modal" 
                                            data-bs-target="#rescheduleModal"
                                            data-appointment-id="{{ $appointment->id }}"
                                            data-doctor-id="{{ $appointment->doctor_user_id }}">
                                            <i class="bx bx-calendar-edit me-1"></i> Reagendar
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item text-info" data-bs-toggle="modal" 
                                            data-bs-target="#scheduleModal" 
                                            data-doctor-id="{{ $appointment->doctor_user_id }}">
                                            <i class="bx bx-calendar me-1"></i> Ver Horário
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

<!-- Modal de Aprovação -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Aprovação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja aprovar esta consulta?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="approveForm" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">Confirmar Aprovação</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Reagendamento -->
<div class="modal fade" id="rescheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reagendar Consulta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="rescheduleForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="new_doctor" class="form-label">Médico</label>
                        <select class="form-select" id="new_doctor" name="new_doctor_id" required>
                            <option value="">Selecione um médico</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="new_date" class="form-label">Nova Data</label>
                        <input type="date" class="form-control" id="new_date" name="new_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_time" class="form-label">Novo Horário</label>
                        <select class="form-select" id="new_time" name="new_time" required>
                            <option value="">Selecione um horário</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="rescheduleForm" class="btn btn-primary">Confirmar Reagendamento</button>
            </div>
        </div>
    </div>
</div>

<script>
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

    // Manipulador do modal de aprovação
    document.querySelectorAll('[data-bs-target="#approveModal"]').forEach(button => {
        button.addEventListener('click', function() {
            const appointmentId = this.getAttribute('data-appointment-id');
            const form = document.getElementById('approveForm');
            form.action = `/appointments/${appointmentId}/approve`;
        });
    });

    // Manipulador do modal de reagendamento
    document.querySelectorAll('[data-bs-target="#rescheduleModal"]').forEach(button => {
        button.addEventListener('click', function() {
            const appointmentId = this.getAttribute('data-appointment-id');
            const currentDoctorId = this.getAttribute('data-doctor-id');
            const appointmentDate = this.closest('tr').querySelector('.appointment-date-time').textContent.trim();
            
            // Converter a data do formato brasileiro (dd/mm/yyyy HH:mm) para o formato do input
            const [datePart, timePart] = appointmentDate.split(' ');
            const [day, month, year] = datePart.split('/');
            
            // Preencher a data atual
            const formattedDate = `${year}-${month}-${day}`;
            document.getElementById('new_date').value = formattedDate;
            
            // Preencher o horário atual
            document.getElementById('new_time').value = timePart;

            const form = document.getElementById('rescheduleForm');
            form.action = `/appointments/${appointmentId}/reschedule`;

            // Carregar e selecionar o médico atual
            axios.get('/autocomplete/doctors')
                .then(response => {
                    const doctorSelect = document.getElementById('new_doctor');
                    doctorSelect.innerHTML = '<option value="">Selecione um médico</option>';
                    
                    response.data.forEach(doctor => {
                        const option = document.createElement('option');
                        option.value = doctor.id;
                        option.textContent = doctor.name;
                        if (doctor.id == currentDoctorId) {
                            option.selected = true;
                        }
                        doctorSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Erro ao carregar médicos:', error);
                    alert('Erro ao carregar lista de médicos');
                });
        });
    });

    // Atualizar a validação do formulário de reagendamento
    document.getElementById('rescheduleForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const newDate = document.getElementById('new_date').value;
        const newTime = document.getElementById('new_time').value;
        
        if (!newDate || !newTime) {
            alert('Por favor, preencha todos os campos');
            return;
        }

        // Verificar se a data não é no passado
        const selectedDateTime = new Date(newDate + ' ' + newTime);
        const now = new Date();
        
        if (selectedDateTime < now) {
            alert('Não é possível reagendar para uma data/hora no passado');
            return;
        }

        this.submit();
    });

    function loadAvailableTimes() {
        const newDate = document.getElementById('new_date').value;
        const doctorId = document.getElementById('new_doctor').value;
        const timeInput = document.getElementById('new_time');

        if (!doctorId) {
            alert('Por favor, selecione um médico primeiro');
            timeInput.value = '';
            return;
        }

        if (!newDate) {
            alert('Por favor, selecione uma data primeiro');
            timeInput.value = '';
            return;
        }

        // Desabilitar o input enquanto carrega
        timeInput.disabled = true;

        fetch(`/available-times?doctor_id=${doctorId}&day=${newDate}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao carregar horários');
                }
                return response.json();
            })
            .then(data => {
                timeInput.disabled = false;

                if (!data || data.length === 0) {
                    alert('Não há horários disponíveis para esta data');
                    timeInput.value = '';
                    return;
                }

                // Criar um datalist para mostrar os horários disponíveis
                let datalistId = 'available-times-list';
                let existingDatalist = document.getElementById(datalistId);
                
                if (!existingDatalist) {
                    existingDatalist = document.createElement('datalist');
                    existingDatalist.id = datalistId;
                    document.body.appendChild(existingDatalist);
                }

                existingDatalist.innerHTML = '';
                data.forEach(time => {
                    const option = document.createElement('option');
                    option.value = time;
                    existingDatalist.appendChild(option);
                });

                // Associar o datalist ao input de tempo
                timeInput.setAttribute('list', datalistId);
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao carregar horários disponíveis. Por favor, tente novamente.');
                timeInput.disabled = false;
            });
    }

    // Adicione estes event listeners ao modal de reagendamento
    document.getElementById('rescheduleModal').addEventListener('show.bs.modal', function () {
        // Adicionar os event listeners quando o modal abrir
        document.getElementById('new_date').addEventListener('change', loadAvailableTimes);
        document.getElementById('new_doctor').addEventListener('change', loadAvailableTimes);
    });

    // Modificar o handler de submit do formulário para incluir a validação do horário
    document.getElementById('rescheduleForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const newDate = document.getElementById('new_date').value;
        const newTime = document.getElementById('new_time').value;
        const doctorId = document.getElementById('new_doctor').value;
        
        if (!newDate || !newTime || !doctorId) {
            alert('Por favor, preencha todos os campos');
            return;
        }

        // Verificar se a data não é no passado
        const selectedDateTime = new Date(newDate + ' ' + newTime);
        const now = new Date();
        
        if (selectedDateTime < now) {
            alert('Não é possível reagendar para uma data/hora no passado');
            return;
        }

        // Verificar se o horário selecionado está na lista de horários disponíveis
        const datalist = document.getElementById('available-times-list');
        const isTimeAvailable = Array.from(datalist.options).some(option => option.value === newTime);
        
        if (!isTimeAvailable) {
            alert('O horário selecionado não está disponível. Por favor, escolha um horário da lista.');
            return;
        }

        this.submit();
    });

    // Adicione este código ao seu script existente
    document.addEventListener('DOMContentLoaded', function() {
        const newDoctorSelect = document.getElementById('new_doctor');
        const newDateInput = document.getElementById('new_date');
        const newTimeSelect = document.getElementById('new_time');

        // Carregar médicos quando o modal abrir
        document.getElementById('rescheduleModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const doctorId = button.getAttribute('data-doctor-id');
            
            // Carregar lista de médicos
            fetch('/autocomplete/doctors')
                .then(response => response.json())
                .then(doctors => {
                    newDoctorSelect.innerHTML = '<option value="">Selecione um médico</option>';
                    doctors.forEach(doctor => {
                        const option = document.createElement('option');
                        option.value = doctor.id;
                        option.textContent = doctor.name;
                        if (doctor.id == doctorId) {
                            option.selected = true;
                        }
                        newDoctorSelect.appendChild(option);
                    });
                    
                    // Se um médico já estiver selecionado, carregue seus horários
                    if (doctorId) {
                        loadAvailableDates(doctorId);
                    }
                });
        });

        // Carregar datas disponíveis quando um médico for selecionado
        newDoctorSelect.addEventListener('change', function() {
            if (this.value) {
                loadAvailableDates(this.value);
            }
        });

        // Carregar horários disponíveis quando uma data for selecionada
        newDateInput.addEventListener('change', function() {
            if (this.value && newDoctorSelect.value) {
                loadAvailableTimes(newDoctorSelect.value, this.value);
            }
        });

        function loadAvailableDates(doctorId) {
            // Limpar data e horário selecionados
            newDateInput.value = '';
            newTimeSelect.innerHTML = '<option value="">Selecione um horário</option>';
            
            // Definir data mínima como hoje
            const today = new Date().toISOString().split('T')[0];
            newDateInput.min = today;
        }

        function loadAvailableTimes(doctorId, date) {
            fetch(`/available-times?doctor_id=${doctorId}&day=${date}`)
                .then(response => response.json())
                .then(times => {
                    newTimeSelect.innerHTML = '<option value="">Selecione um horário</option>';
                    
                    if (times.length === 0) {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'Nenhum horário disponível';
                        option.disabled = true;
                        newTimeSelect.appendChild(option);
                    } else {
                        times.forEach(time => {
                            const option = document.createElement('option');
                            option.value = time;
                            option.textContent = time;
                            newTimeSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar horários:', error);
                    newTimeSelect.innerHTML = '<option value="">Erro ao carregar horários</option>';
                });
        }
    });
</script>
@endsection