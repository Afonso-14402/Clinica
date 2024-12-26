@extends('layouts.admin')

@section('title', 'Regitar - Clínica')

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
    <div class="container mt-5">
        

        <!-- Mensagem de sucesso -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Exibir erros de validação -->
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulário de registro -->
        <form action="{{ route('registar.paciente') }}" method="POST">
            @csrf
            <div class="col-md-6">
              <div class="card">
                <h5 class="card-header">Registrar Paciente</h5>
                <div class="card-body">
                  <div class="md-4">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                  </div>
                  <br>
                  <div class="md-4">
                    <label for="last_name" class="form-label">Sobrenome</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                  </div>
                  <br>
                  <div class="mb-4">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email">
                  </div>
                  <div class="mb-4">
                    <label for="current_password" class="form-label">Senha</label>
                    <div class="input-group input-group-merge">
                        <input
                            type="password"
                            id="password"
                            class="form-control"
                            name="password"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password" />
                        <span class="input-group-text cursor-pointer toggle-password">
                            <i class="bx bx-hide"></i>
                        </span>
                    </div>
                </div>
                  <div class="mb-4">
                    <label for="current_password" class="form-label">Senha</label>
                    <div class="input-group input-group-merge">
                        <input
                            type="password"
                            id="password_confirmation"
                            class="form-control"
                            name="password_confirmation"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password" />
                        <span class="input-group-text cursor-pointer toggle-password">
                            <i class="bx bx-hide"></i>
                        </span>
                    </div>
                </div>
                  <div class="mb-4">
                    <label for="birth_date" class="form-label">Data de Nascimento</label>
                    <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                  </div>
                  <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
              </div>
              
            </div>
            
        </form>
    </div>
@endsection



