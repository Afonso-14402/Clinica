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
      
                          <!-- Campo de paciente -->
                          <div class="mb-3">
                            <label for="patient_user_id" class="form-label">Paciente</label>
                              <div>
                                  <input 
                                      type="text" 
                                      id="patient-search" 
                                      placeholder="Digite o nome do médico..." 
                                      class="form-control"
                                      autocomplete="off"
                                  />
                                  <ul id="patient-list" class="list-group mt-2" style="display: none;"></ul>
                                  <input type="hidden" name="patient_user_id" id="patient_user_id" />
                              </div>
                          </div>
      
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
                        
                        <!-- Campo de especialidade -->
                        <div class="mb-3">
                            <label for="specialties_id" class="form-label">Especialidade</label>
                            <select class="form-select" name="specialties_id" required>
                                <!-- Opções preenchidas dinamicamente -->
                            </select>
                        </div>
                          <!-- Campo de data e hora -->
                          <div class="mb-3">
                              <label for="appointment_date_time" class="form-label">Data e Hora</label>
                              <input type="datetime-local" class="form-control" name="appointment_date_time" required>
                          </div>
      
                          <div class="modal-footer">
                              <button type="submit" class="btn btn-primary">Agendar Consulta</button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>
      <div class="col-md-8">
        <h4>Calendário</h4>
        <div class="card">
            <div class="card-body">
              <div class="container mt-5">
                {{-- For Search --}}
                <div class="row">
                    <div class="col-md-6">
                      <div class="input-group">
                      </div>
                        <input type="text" id="searchInput" class="form-control" placeholder="{{__('Enter search term')}}">
                        <div class="input-group-append">
                            <button id="searchButton" class="btn btn-primary">{{__('Search')}}</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="btn-group mb-3" role="group" aria-label="Calendar Actions">
                            <button id="exportButton" class="btn btn-success">{{__('Export Calendar')}}</button>
                        </div>
                        
        
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <p><strong>Title:</strong> <span id="eventTitle"></span></p>
                        <p><strong>Date:</strong> <span id="eventDate"></span></p>
                        <p><strong>Description:</strong> <span id="eventDescription"></span></p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
        
                <div class="card">
                    <div class="card-body">
                        <div id="calendar" style="width: 100%;height:100vh"></div>
        
                    </div>
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
        
            <script type="text/javascript">
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
        
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    initialView: 'dayGridMonth',
                    timeZone: 'UTC',
                    events: '/events',
                    editable: true,
                    
                    // Quando o usuário clica em um evento
                    eventClick: function(info) {
                        // Preencher o modal com as informações do evento
                        var event = info.event;
                        
                        // Exemplo de como preencher as informações do evento no modal
                        document.getElementById('eventTitle').innerText = event.title;
                        document.getElementById('eventDate').innerText = event.start.toLocaleString();
                        document.getElementById('eventDescription').innerText = event.extendedProps.description || 'No description available';
                        
                        // Mostrar o modal
                        $('#eventModal').modal('show');
                    }
                });

                calendar.render();

        
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
        
        
                // Função de exportação
                document.getElementById('exportButton').addEventListener('click', function() {
                    var events = calendar.getEvents().map(function(event) {
                        return {
                            title: event.title,
                            start: event.start ? event.start.toISOString() : null,
                           
                            color: event.backgroundColor,
                        };
                    });
        
                    var wb = XLSX.utils.book_new();
        
                    var ws = XLSX.utils.json_to_sheet(events);
        
                    XLSX.utils.book_append_sheet(wb, ws, 'Events');
        
                    var arrayBuffer = XLSX.write(wb, {
                        bookType: 'xlsx',
                        type: 'array'
                    });
        
                    var blob = new Blob([arrayBuffer], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });
        
                    var downloadLink = document.createElement('a');
                    downloadLink.href = URL.createObjectURL(blob);
                    downloadLink.download = 'events.xlsx';
                    downloadLink.click();
                })         
            </script>

           </div>
        </div>
      </div>
    </div>
</div>

@endsection


  
