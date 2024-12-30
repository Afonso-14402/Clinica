
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
    <h2 class="mb-4">Adicionar Usuário</h2>
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a >Usuários</a></li>
            <li class="breadcrumb-item"><a href="#">Perfil de Usuário</a></li>
            <li class="breadcrumb-item active" aria-current="page">Adicionar Paciente</li>
        </ol>
    </nav>
    <!-- Card com Abas -->
    <div class="card shadow-sm">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="pacienteTab" role="tablist">
                @foreach (['Dados de Acesso', 'Dados Pessoais', 'Dados Complementares', 'Convênios', 'Prontuário Médico'] as $index => $tab)
                <li class="nav-item" role="presentation">
                    <button 
                        class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                        id="{{ Str::slug($tab) }}-tab" 
                        data-bs-toggle="tab" 
                        data-bs-target="#{{ Str::slug($tab) }}" 
                        type="button" 
                        role="tab">
                        {{ $tab }}
                    </button>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content" id="pacienteTabContent">
                <!-- Aba: Dados de Acesso -->
                <div class="tab-pane fade show active" id="dados-de-acesso" role="tabpanel" aria-labelledby="dados-de-acesso-tab">
                    <form>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Digite o e-mail do paciente" required>
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha" placeholder="Defina uma senha" required>
                        </div>
                    </form>
                </div>
                
                <!-- Aba: Dados Pessoais -->
                <div class="tab-pane fade" id="dados-pessoais" role="tabpanel" aria-labelledby="dados-pessoais-tab">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome completo" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="data-nascimento" class="form-label">Data de Nascimento</label>
                                <input type="date" class="form-control" id="data-nascimento" name="data_nascimento" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="tel" class="form-control" id="telefone" name="telefone" placeholder="(XX) XXXXX-XXXX">
                        </div>
                    </form>
                </div>

                <!-- Aba: Dados Complementares -->
                <div class="tab-pane fade" id="dados-complementares" role="tabpanel" aria-labelledby="dados-complementares-tab">
                    <form>
                        <div class="mb-3">
                            <label for="endereco" class="form-label">Endereço</label>
                            <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Digite o endereço completo">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" class="form-control" id="cidade" name="cidade">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <input type="text" class="form-control" id="estado" name="estado">
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Aba: Convênios -->
                <div class="tab-pane fade" id="convenios" role="tabpanel" aria-labelledby="convenios-tab">
                    <form>
                        <div class="mb-3">
                            <label for="convenio" class="form-label">Convênio</label>
                            <input type="text" class="form-control" id="convenio" name="convenio" placeholder="Informe o convênio do paciente">
                        </div>
                        <div class="mb-3">
                            <label for="numero-carteira" class="form-label">Número da Carteira</label>
                            <input type="text" class="form-control" id="numero-carteira" name="numero_carteira">
                        </div>
                    </form>
                </div>

                <!-- Aba: Prontuário Médico -->
                <div class="tab-pane fade" id="prontuario-medico" role="tabpanel" aria-labelledby="prontuario-medico-tab">
                    <p>Cadastro de Prontuário</p>
                    <p>O cadastro da Anamnese, exame físico e/ou Hipótese diagnóstica será armazenado como ficha inicial do paciente.</p>
                    <button class="btn btn-success">Iniciar Consulta</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


