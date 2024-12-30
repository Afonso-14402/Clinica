@extends('layouts.admin')

@section('title', 'Regitar - Clínica')

@section('content')

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
        <form action="{{ route('registar.medico') }}" method="POST">
            @csrf
            <div class="col-md-6">
              <div class="card">
                <h5 class="card-header">Registrar Paciente</h5>
                <div class="card-body">
                  <div class="mb-4 row">
                    <div class="col-md-6">
                      <label for="name" class="form-label">Nome</label>
                      <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                      <label for="last_name" class="form-label">Sobrenome</label>
                      <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                    </div>
                  </div>
                  
                  <div class="mb-4">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
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

                  <div class="mb-4">
                    <div class="mb-4">
                        <label for="role" class="form-label">Cargo</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="role" 
                            name="role" 
                            value="Médico" 
                            readonly>
                    </div>
                    <input type="hidden" name="role_id" value="2">
                    
                    <!-- Campo de seleção para especialidades -->
                    <div class="mb-4">
                        <label for="specialties" class="form-label">Especialidade</label>
                        <select class="form-control" name="specialties[]" id="specialties" multiple required>
                            @foreach ($specialties as $specialty)
                                <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Segure Ctrl  para selecionar várias especialidades.</small>
                    </div>
                                    
                <input type="hidden" name="role_id" value="2">                               
                  <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
              </div>
              
            </div>
            
        </form>
    </div>
@endsection