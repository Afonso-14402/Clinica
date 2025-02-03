@extends('layouts.admin')

@section('content')
<div class="container mt-5">
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
        <!-- Título com ícone -->
        <div class="text-center mb-5">
            <i class="bx bx-file text-primary" style="font-size: 3rem;"></i>
           
            <h4 class="text-muted mb-2">Paciente: {{ $appointment->patient->name }}</h4>
            <p class="text-muted">Preencha os dados da consulta com atenção</p>
        </div>

        <!-- Card Principal com hover effect -->
        <div class="card shadow-lg hover-shadow-lg transition-all duration-300">
            <div class="card-body p-4">
                <!-- Informações do Paciente com melhor organização -->
                <div class="flex-grow-1">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-calendar text-primary me-2"></i>
                                <span>{{ \Carbon\Carbon::parse($appointment->appointment_date_time)->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-user-md text-primary me-2"></i>
                                <span>Dr(a). {{ $appointment->doctor->name }}</span>
                            </div>
                        </div>
                        @if($appointment->specialties_id)
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-badge-check text-primary me-2"></i>
                                <span>{{ $appointment->specialty->name }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulário de Relatório Médico -->
        <div class="card mt-4 shadow-lg">
            <div class="card-body p-4">
                <h4 class="text-primary mb-4 d-flex align-items-center">
                    <i class="bx bx-notepad me-2"></i>
                    Relatório Médico
                </h4>

                <!-- Formulário com visual melhorado -->
                <form action="{{ route('consulta.salvarRelatorio', $appointment->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">
                        <!-- Seção 1: O que o paciente sente -->
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea 
                                    class="form-control" 
                                    id="sintomas" 
                                    name="sintomas" 
                                    style="height: 100px"
                                    required></textarea>
                                <label for="sintomas">
                                    <i class="bx bx-health me-2"></i>
                                    O que o paciente sente?
                                </label>
                            </div>
                        </div>

                        <!-- Seção 2: Diagnóstico -->
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea 
                                    class="form-control" 
                                    id="diagnostico" 
                                    name="diagnostico" 
                                    style="height: 100px"
                                    required></textarea>
                                <label for="diagnostico">
                                    <i class="bx bx-search-alt-2 me-2"></i>
                                    Diagnóstico
                                </label>
                            </div>
                        </div>

                        <!-- Seção 3: Tratamento -->
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea 
                                    class="form-control" 
                                    id="tratamento" 
                                    name="tratamento" 
                                    style="height: 100px"
                                    required></textarea>
                                <label for="tratamento">
                                    <i class="bx bx-plus-medical me-2"></i>
                                    Tratamento
                                </label>
                            </div>
                        </div>

                        <!-- Seção 4: Observações -->
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea 
                                    class="form-control" 
                                    id="observacoes" 
                                    name="observacoes" 
                                    style="height: 100px"
                                    required></textarea>
                                <label for="observacoes">
                                    <i class="bx bx-note me-2"></i>
                                    Observações
                                </label>
                            </div>
                        </div>

                        <!-- Nova Seção: Upload de Imagens de Exames -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">
                                        <i class="bx bx-images text-primary me-2"></i>
                                        Imagens de Exames
                                    </h5>
                                    
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <label for="exam_images" class="form-label mb-0">
                                                Adicionar imagens de exames
                                            </label>
                                            <span class="badge bg-info">Máximo 5 arquivos</span>
                                        </div>
                                        <input 
                                            type="file" 
                                            class="form-control" 
                                            id="exam_images" 
                                            name="exam_images[]" 
                                            accept="image/*,.pdf"
                                            multiple
                                        >
                                        <small class="text-muted">
                                            Formatos aceitos: JPG, PNG, PDF (Máximo 5MB por arquivo)
                                        </small>
                                    </div>

                                    <!-- Preview das imagens -->
                                    <div id="imagePreview" class="d-flex flex-wrap gap-2 mt-3"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Botões de ação -->
                        <div class="col-12 d-flex gap-2 justify-content-end mt-4">
                            <button type="button" class="btn btn-outline-secondary">
                                <i class="bx bx-x me-2"></i>
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-check me-2"></i>
                                Finalizar Consulta
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Adicione este script no final do arquivo -->
    @push('scripts')
    <script>
        document.getElementById('exam_images').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';

            if (this.files.length > 5) {
                alert('Por favor, selecione no máximo 5 arquivos.');
                this.value = '';
                return;
            }

            // Criar um FormData para enviar as imagens
            const formData = new FormData();

            Array.from(this.files).forEach((file, index) => {
                if (file.size > 5 * 1024 * 1024) {
                    alert(`O arquivo ${file.name} excede o tamanho máximo de 5MB`);
                    return;
                }

                // Adicionar arquivo ao FormData
                formData.append(`exam_images[]`, file);

                const div = document.createElement('div');
                div.className = 'position-relative';

                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.className = 'img-thumbnail';
                    img.style.maxWidth = '150px';
                    img.style.height = '150px';
                    div.appendChild(img);
                } else {
                    const icon = document.createElement('i');
                    icon.className = 'bx bxs-file-pdf text-danger';
                    icon.style.fontSize = '48px';
                    div.appendChild(icon);
                    
                    const fileName = document.createElement('p');
                    fileName.textContent = file.name;
                    fileName.className = 'small mt-1';
                    div.appendChild(fileName);
                }

                preview.appendChild(div);
            });

            // Adicionar token CSRF ao FormData
            formData.append('_token', '{{ csrf_token() }}');
        });
    </script>
    @endpush
@endsection
