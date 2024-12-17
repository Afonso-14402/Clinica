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
                    <!--
                        <div class="calendar" id="calendar">
                                Simulação do calendário 
                            <ul class="list-unstyled">
                            <div id="calendar"></div>

                          
                            </ul>
                        </div>
                    -->
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
                  
                            <div class="mb-3">
                              <label for="patient_user_id" class="form-label">Paciente</label>
                              <select class="form-select" name="patient_user_id" required>
                                  @foreach($patients as $patient)
                                      <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                                  @endforeach
                              </select>
                            </div>
                            <div class="mb-3">
                              <label for="doctor_user_id" class="form-label">Médico</label>
                              <select class="form-select" name="doctor_user_id" required>
                                  @foreach($doctors as $doctor)
                                      <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                  @endforeach
                              </select>
                            </div>
                            <div class="mb-3">
                              <label for="specialties_id" class="form-label">Especialidade</label>
                              <select class="form-select" name="specialties_id" required>
                                  @foreach($specialties as $specialty)
                                      <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                  @endforeach
                              </select>
                            </div>
                  
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
        


    </div>
</div>


<script>
    document.getElementById('eventForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Evento salvo com sucesso!');
        document.getElementById('addEventModal').classList.remove('show');
    });
</script>


@endsection
