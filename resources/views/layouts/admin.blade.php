<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <title>@yield('title', 'Clínica')</title>
    <style>
        .sidebar {
            background: #343a40;
            color: white;
            min-height: 100vh;
        }
        .nav-link.active {
            background: #007bff !important;
        }
        .nav-link:hover {
            background: #495057 !important;
        }
        /* Ajustando o conteúdo principal para ocupar o espaço restante */
        .main-content {
            flex: 1;
            padding: 20px;
        }
        /* Adicionando alguma margem para o lado */
        .d-flex {
            display: flex;
        }
    </style>
</head>
<body>
    <main class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column p-3" style="width: 280px;">
            <a href="/" class="d-flex align-items-center mb-3 text-white text-decoration-none">
                <span class="fs-4">Clínica</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('user.index') }}" class="nav-link text-white active">Home</a>
                </li>
                <li><a href="{{ route('dashboard') }}"class="nav-link text-white">Dashboard</a></li>
                <li><a href="{{ route('register.show') }}" class="nav-link text-white">Pacientes</a></li>
                <li><a href="#" class="nav-link text-white">Consultas</a></li>
                <li><a href="#" class="nav-link text-white">Relatórios</a></li>
            </ul>
            
            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="https://github.com/mdo.png" alt="Foto de Perfil" width="32" height="32" class="rounded-circle me-2">
                    <strong>USER</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                    <li><a class="dropdown-item" href="#">Configurações</a></li>
                    <li><a href="{{ route('login.destroy') }}" class="dropdown-item">Sair</a></li>
                </ul>
            </div>
        </div>

        <!-- Conteúdo Principal -->
        <div class="main-content">
            @yield('content')
        </div>
    </main>
</body>
</html>
