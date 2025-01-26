@extends('layouts.admin')

@section('title', 'Agendar Consulta')

@section('content')
    
<div class="container py-5">
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
      <div class="container mt-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <h4 class="alert-heading d-flex align-items-center">
                    <span class="alert-icon rounded-circle"><i class="bx bx-coffee"></i></span>
                    Sucesso!
                </h4>
                <hr>
                <p class="mb-0">{{ session('success') }}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <h4 class="alert-heading d-flex align-items-center">
                    <span class="alert-icon rounded-circle"><i class="bx bx-error"></i></span>
                    Erro!
                </h4>
                <hr>
                <p class="mb-0">{{ session('error') }}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
    <div class="row">
        <!-- Lado Esquerdo: Calendário -->
        <div class="col-md-4">
            <h4>Agenda</h4>
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addEventModal">
                        + Agendar consulta 
                    </button>
                </div>
                
                  <div class="card-body">
                    
                </div>
                    
               
            </div>
        </div>
        <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="addEventModalLabel">Agendar Consulta</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <form method="POST" action="{{ route('appointments.store') }}">
                          @csrf
      
                          <!-- Campo de paciente - mantém o original -->
                          <div class="mb-3">
                              <label for="patient_user_id" class="form-label">Paciente</label>
                              <div>
                                  <input 
                                      type="text" 
                                      id="patient-search" 
                                      placeholder="Digite o nome do paciente..." 
                                      class="form-control"
                                      autocomplete="off"
                                  />
                                  <ul id="patient-list" class="list-group mt-2" style="display: none;"></ul>
                                  <input type="hidden" name="patient_user_id" id="patient_user_id" />
                              </div>
                          </div>
      
                          <!-- Campo de médico - mantém o original -->
                          <div class="mb-3">
                              <label for="doctor_user_id" class="form-label">Médico</label>
                              <div>
                                  <input 
                                      type="text" 
                                      id="doctor-search" 
                                      placeholder="Digite o nome do médico..." 
                                      class="form-control"
                                      autocomplete="off"
                                  />
                                  <ul id="doctor-list" class="list-group mt-2" style="display: none;"></ul>
                                  <input type="hidden" name="doctor_user_id" id="doctor_user_id" />
                              </div>
                          </div>
      
                          <!-- Campo de especialidade - mantém o original -->
                          <div class="mb-3">
                              <label for="specialties_id" class="form-label">Especialidade</label>
                              <select class="form-select" name="specialties_id" required>
                                  <!-- Opções preenchidas dinamicamente -->
                              </select>
                          </div>
      
                          <!-- NOVOS campos de data e hora separados -->
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
      
                          <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                              <button type="submit" class="btn btn-primary">Agendar Consulta</button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>
      <div class="col-md-8">
        <h4>Calendário</h4>
        
        <div class="card" style="width: 36rem;">

                <div class="container mt-5">
    
                    <!-- Campo de busca e botões de ação -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control" placeholder="Introduza o termo de pesquisa">
                                <button id="searchButton" class="btn btn-primary">Pesquisar</button>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <button id="exportButton" class="btn btn-success">Exportar Calendário</button>
                        </div>
                    </div>
                    <!-- Calendário -->
                    
                    <div id="calendar" style="width: 100%; height: 100vh;"></div>          
                    
                </div>
          </div>
                <!-- Modal para detalhes de eventos -->
                <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title" id="eventModalLabel">Detalhes da consulta</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                              <p><strong>Paciente:</strong> <span id="eventPatient"></span></p>
                              <p><strong>Médico:</strong> <span id="eventDoctor"></span></p>
                              <p><strong>Data:</strong> <span id="eventDate"></span></p>
                              <p><strong>Hora:</strong> <span id="eventTime"></span></p>
                              <p><strong>Tipo de Consulta:</strong> <span id="eventType"></span></p>
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                          </div>
                      </div>
                  </div>
              </div>
    
                <!-- Scripts -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    
                <script type="text/javascript">
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
    
                    // Inicialização do calendário
                    var calendarEl = document.getElementById('calendar');
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                      locale: 'pt-br', // Isso já inclui as traduções necessárias
                      buttonText: {
                          today: 'Hoje',
                          month: 'Mês',
                          week: 'Semana',
                          day: 'Dia',
                      },
                      headerToolbar: {
                          left: 'prev,next today',
                          center: 'title',
                          right: 'dayGridMonth,timeGridWeek,timeGridDay'
                      },
                      initialView: 'dayGridMonth',
                      timeZone: 'UTC',
                      events: '/events',
                      editable: true,
                      eventClick: function(info) {
                          var event = info.event;
                          document.getElementById('eventPatient').innerText = event.extendedProps.patient || 'N/D';
                          document.getElementById('eventDoctor').innerText = event.extendedProps.doctor || 'N/D';
                          document.getElementById('eventDate').innerText = event.start.toLocaleDateString('pt-PT');
                          document.getElementById('eventTime').innerText = event.start.toLocaleTimeString('pt-PT');
                          document.getElementById('eventType').innerText = event.extendedProps.type || 'N/D';
                          $('#eventModal').modal('show');
                      }
                    });
                    calendar.render();
    
                    // Função de busca de eventos
                    document.getElementById('searchButton').addEventListener('click', function() {
                        var searchKeywords = document.getElementById('searchInput').value.toLowerCase();
                        filterAndDisplayEvents(searchKeywords);
                    });
    
                    function filterAndDisplayEvents(searchKeywords) {
                        $.ajax({
                            method: 'GET',
                            url: `/events/search?title=${searchKeywords}`,
                            success: function(response) {
                                calendar.removeAllEvents();
                                calendar.addEventSource(response);
                            },
                            error: function(error) {
                                console.error('Error searching events:', error);
                            }
                        });
                    }
    
                   // Função para exportar eventos com mais informações
                    document.getElementById('exportButton').addEventListener('click', function() {
                        var events = calendar.getEvents().map(function(event) {
                            return {
                                'Paciente': event.extendedProps.patient,       // Nome do paciente
                                'Médico': event.extendedProps.doctor,         // Nome do médico
                                'Hora da Consulta': event.extendedProps.appointment_time, // Hora da consulta
                                'Tipo da Consulta': event.extendedProps.type,  // Tipo da consulta
                                'Início': event.start ? event.start.toISOString() : null, // Data e hora do início
                            };
                        });

                        // Criação do livro de trabalho e da planilha
                        var wb = XLSX.utils.book_new();
                        var ws = XLSX.utils.json_to_sheet(events);

                        // Adicionando a planilha ao livro de trabalho
                        XLSX.utils.book_append_sheet(wb, ws, 'Eventos');

                        // Gerando o arquivo XLSX
                        var arrayBuffer = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
                        var blob = new Blob([arrayBuffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });

                        // Criando o link para download
                        var downloadLink = document.createElement('a');
                        downloadLink.href = URL.createObjectURL(blob);
                        downloadLink.download = 'eventos.xlsx';
                        downloadLink.click();
                    });

                </script>
    </div>    
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Pegar os valores dos campos
        const appointmentDay = document.getElementById('appointment_day').value;
        const appointmentTime = document.getElementById('appointment_time').value;
        
        // Combinar data e hora
        const appointmentDateTime = appointmentDay + ' ' + appointmentTime;
        
        // Criar um campo hidden para a data/hora combinada
        let dateTimeInput = document.getElementById('appointment_date_time');
        if (!dateTimeInput) {
            dateTimeInput = document.createElement('input');
            dateTimeInput.type = 'hidden';
            dateTimeInput.name = 'appointment_date_time';
            dateTimeInput.id = 'appointment_date_time';
            form.appendChild(dateTimeInput);
        }
        dateTimeInput.value = appointmentDateTime;
        
        // Enviar o formulário
        form.submit();
    });
});

function loadAvailableTimes() {
    const appointmentDay = document.getElementById('appointment_day').value;
    const doctorId = document.getElementById('doctor_user_id').value;
    const timeSelect = document.getElementById('appointment_time');

    if (!appointmentDay || !doctorId) {
        timeSelect.innerHTML = '<option value="" disabled selected>Selecione um médico e uma data primeiro</option>';
        return;
    }

    timeSelect.innerHTML = '<option value="" disabled selected>Carregando horários...</option>';
    timeSelect.disabled = true;

    fetch(`/available-times?doctor_id=${doctorId}&day=${appointmentDay}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao carregar horários');
            }
            return response.json();
        })
        .then(data => {
            timeSelect.disabled = false;
            timeSelect.innerHTML = '';

            if (!data.times || data.times.length === 0) {
                timeSelect.innerHTML = '<option value="" disabled selected>Sem horários disponíveis</option>';
                return;
            }

            // Adiciona a opção padrão
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Selecione um horário';
            defaultOption.selected = true;
            defaultOption.disabled = true;
            timeSelect.appendChild(defaultOption);

            // Adiciona os horários disponíveis
            data.times.forEach(time => {
                const option = document.createElement('option');
                option.value = time;
                option.textContent = time;
                timeSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Erro:', error);
            timeSelect.innerHTML = '<option value="" disabled selected>Erro ao carregar horários</option>';
            timeSelect.disabled = true;
        });
}
</script>

@endsection


  
