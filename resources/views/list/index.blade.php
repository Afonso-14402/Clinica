@extends('layouts.admin')

@section('title', 'Lista de Médicos')

<style>
.modal-body {
    background-color: #f9f9f9;
    border-radius: 6px;
    padding: 20px;
}

.list-group-item {
    border: none;
    padding: 15px 0;
}

.list-group-item:not(:last-child) {
    border-bottom: 1px solid #e9ecef;
}

.form-check-label {
    font-weight: bold;
    margin-right: 15px;
}

.status-icon {
    font-size: 1.2rem;
    vertical-align: middle;
    transition: all 0.3s ease;
    cursor: pointer;
}

.status-icon.status-active {
    color: #28a745;
}

.status-icon.status-inactive {
    color: #ffc107;
}

.status-icon:hover {
    transform: scale(1.2);
}

.table th, .table td {
    vertical-align: middle;
    text-align: center;
}

.status-icon {
    font-size: 1.2rem;
    vertical-align: middle;
    transition: all 0.3s ease;
    cursor: pointer;
}

.status-icon:hover {
    transform: scale(1.2);
}

form button {
    cursor: pointer;
}

/* Container geral do formulário */
.schedule-form-container {
    max-width: 700px;
    margin: 2rem auto;
    padding: 2rem;
    border: 1px solid #e3e3e3;
    border-radius: 10px;
    background-color: #ffffff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    font-family: 'Arial', sans-serif;
}

/* Título e subtítulo */
.form-title {
    font-size: 1.8rem;
    font-weight: bold;
    color: #2c3e50;
    text-align: center;
    margin-bottom: 0.5rem;
}

.form-subtitle {
    font-size: 1rem;
    color: #7f8c8d;
    text-align: center;
    margin-bottom: 1.5rem;
}

/* Lista de itens */
.schedule-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* Cada item do horário */
.schedule-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #f9f9f9;
    padding: 1rem;
    border: 1px solid #dcdcdc;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.schedule-item:hover {
    background-color: #f0f0f0;
    border-color: #cccccc;
}

/* Checkbox e label do dia */
.day-checkbox {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-check-label {
    font-weight: 600;
    font-size: 1rem;
    color: #34495e;
}

/* Campos de horário */
.time-inputs {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.time-field {
    width: 120px;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 0.9rem;
}

.time-separator {
    font-size: 0.9rem;
    color: #7f8c8d;
    font-weight: bold;
}

/* Botão remover */
.btn-remove {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    font-weight: bold;
    color: #e74c3c;
    background-color: transparent;
    border: 1px solid #e74c3c;
    border-radius: 5px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-remove:hover {
    background-color: #e74c3c;
    color: #fff;
}

/* medico modal */
        .modal {
            display: none; /* Modal começa oculto */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .custom-modal {
            background-color: rgb(239, 239, 239);
            padding: 20px;
            border-radius: 8px;
            width: 150%;           /* Reduz a largura para 80% da tela */
            max-width: 700px;     /* Aumenta a largura máxima para 700px */
        }




</style>

@section('content')

<!-- Navbar -->
<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme shadow-sm" id="layout-navbar">
    <!-- Navbar Content -->
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Notifications -->
            <li class="nav-item dropdown-notifications navbar-dropdown dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="bx bx-bell bx-sm"></i>
                    <span class="badge rounded-pill bg-danger badge-notifications"></span>
                </a>
            </li>
            <!-- User Profile -->
            <li class="nav-item dropdown-user navbar-dropdown dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}" alt="User Avatar" class="avatar">
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item">
                            <div class="d-flex">
                                <div class="avatar avatar-online me-3">
                                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}" alt="User Avatar">
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                    <small class="text-muted">{{ $user->role->role }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('settings.index') }}">
                            <i class="bx bx-cog me-2"></i>
                            <span>Configurações</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('login.destroy') }}">
                            <i class="bx bx-power-off me-2"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<div class="container py-5">
    <div class="card">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            
            Lista de Médicos
            
            <div class="col-md-4">
                <input type="text" id="doctorSearch" class="form-control" placeholder="Pesquisar por nome...">
            </div>

            <button id="exportButton" class="btn btn-success btn-sm">
                <i class="bx bx-download"></i> Exportar para Excel
            </button>

            <button class="btn btn-success" id="novoMedicoBtn">Novo Médico</button>
            
            
        </h5>
        <div class="table-responsive text-nowrap" id="doctorTable">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Especialidade</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="doctorTableBody">
                    @foreach($doctors as $doctor)
                    <tr>
                        <td>{{ $doctor->name }}</td>
                        <td>{{ $doctor->email }}</td>
                        <td>
                            @if ($doctor->specialties->isNotEmpty())
                                {{ $doctor->specialties->pluck('name')->join('/') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('doctors.toggleStatus', $doctor->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" style="border: none; background: none; padding: 0;">
                                    <i class="status-icon {{ $doctor->status ? 'status-active bx bx-check-circle' : 'status-inactive bx bx-x-circle' }}" data-toggle="tooltip" title="{{ $doctor->status ? 'Ativo - Clique para desativar' : 'Inativo - Clique para ativar' }}"></i>
                                </button>
                            </form>
                            
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Ações
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('doctors.edit', $doctor->id) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Editar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#schedulesModal" data-doctor-id="{{ $doctor->id }}">
                                            <i class="bx bx-calendar me-1"></i> Horários
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este médico?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bx bx-trash me-1"></i> Excluir
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <nav>
                <ul class="pagination justify-content-center">
                    @if ($doctors->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">Anterior</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $doctors->previousPageUrl() }}">Anterior</a>
                        </li>
                    @endif

                    @for ($i = 1; $i <= $doctors->lastPage(); $i++)
                        <li class="page-item {{ $doctors->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $doctors->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    @if ($doctors->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $doctors->nextPageUrl() }}">Próxima</a>
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



    <!-- Modal para horários -->
<div class="modal fade" id="schedulesModal" tabindex="-1" aria-labelledby="schedulesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="schedulesModalLabel">Horários do Médico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="scheduleForm">
                    <!-- Início do design dos horários -->
                    <div class="row">
                        <div class="col-12">
                            <div id="scheduleContent" class="p-2">
                                <!-- Conteúdo será preenchido pelo JavaScript -->
                                <p>Carregando...</p>
                            </div>
                        </div>
                    </div>
                    <!-- Fim do design dos horários -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" id="saveScheduleButton">Salvar</button>
            </div>
        </div>
    </div>
</div>
    <!-- Modal para medicos -->
    <div class="modal" id="novoMedicoModal" tabindex="-1" style="display: none;"> 
        <div class="modal-dialog custom-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Novo Médico</h5>
                    <button type="button" class="btn-close" id="closeModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulário de registro do médico -->
                    <form action="{{ route('registar.medico') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <!-- Nome e Sobrenome -->
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Sobrenome</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>
    
                        <div class="row g-3 mt-3">
                            <!-- E-mail e Data de Nascimento -->
                            <div class="col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="birth_date" class="form-label">Data de Nascimento</label>
                                <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                            </div>
                        </div>
    
                        <!-- Senhas -->
                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Senha</label>
                                <div class="input-group input-group-merge">
                                    <input
                                        type="password"
                                        id="password"
                                        class="form-control"
                                        name="password"
                                        placeholder="••••••••••••"
                                        aria-describedby="password">
                                    <span class="input-group-text toggle-password">
                                        <i class="bx bx-hide"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                                <div class="input-group input-group-merge">
                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        class="form-control"
                                        name="password_confirmation"
                                        placeholder="••••••••••••"
                                        aria-describedby="password">
                                    <span class="input-group-text toggle-password">
                                        <i class="bx bx-hide"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
    
                        <!-- Cargo e Especialidade -->
                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label for="role" class="form-label">Cargo</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="role" 
                                    name="role" 
                                    value="Médico" 
                                    readonly>
                                <input type="hidden" name="role_id" value="2">
                            </div>
                            <div class="col-md-6">
                                <label for="specialties" class="form-label">Especialidade</label>
                                <select class="form-control" name="specialties[]" id="specialties" multiple required>
                                    @foreach ($specialties as $specialty)
                                        <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
    
                        <!-- Botão de Envio -->
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const scheduleModal = document.getElementById('schedulesModal');
    const scheduleContent = document.getElementById('scheduleContent');

    scheduleModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const doctorId = button.getAttribute('data-doctor-id');

        // ID do médico no modal
        scheduleModal.setAttribute('data-doctor-id', doctorId);

        scheduleContent.innerHTML = '<p>Carregando...</p>';

        fetch(`/doctor-schedules/${doctorId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    scheduleContent.innerHTML = renderScheduleForm(data.schedules);
                } else {
                    scheduleContent.innerHTML = `<p>${data.message}</p>`;
                }
            })
            .catch(() => {
                scheduleContent.innerHTML = '<p>Erro ao carregar horários.</p>';
            });
    });

    function renderScheduleForm(schedules) {
    const days = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];

    let formHtml = `
        <div class="list-group shadow-sm rounded-3">
            <h5 class="mb-4 text-primary text-center fw-bold">Calendário</h5>
    `;

    days.forEach((day, index) => {
        const schedule = schedules.find(s => s.day_of_week == index) || { start_time: '', end_time: '', is_unavailable: false };
        const isUnavailable = schedule.is_unavailable ? 'checked' : '';
        const disabled = schedule.is_unavailable ? 'disabled' : '';

        formHtml += `
            <div class="list-group-item p-3 d-flex align-items-center justify-content-between border-0 border-bottom">
                <!-- Day Checkbox -->
                <div class="form-check form-switch">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        name="days[${index}][is_unavailable]" 
                        id="day-${index}-unavailable" 
                        ${isUnavailable}
                    >
                    <label class="form-check-label ms-2 fw-bold text-dark" for="day-${index}-unavailable">${day}</label>
                </div>

                <!-- Time Inputs -->
                <div class="d-flex align-items-center gap-3">
                    <input 
                        type="time" 
                        name="days[${index}][start_time]" 
                        value="${schedule.start_time}" 
                        class="form-control form-control-sm" 
                        style="width: 110px;" 
                        ${disabled}
                    >
                    <span class="fw-bold text-muted">to</span>
                    <input 
                        type="time" 
                        name="days[${index}][end_time]" 
                        value="${schedule.end_time}" 
                        class="form-control form-control-sm" 
                        style="width: 110px;" 
                        ${disabled}
                    >
                </div>
            </div>
        `;
    });

    formHtml += `
        </div>
    `;
    return formHtml;
}


// Desabilitar inputs de horário ao marcar "Unavailable"
document.addEventListener('change', (e) => {
    if (e.target.matches('[name$="[is_unavailable]"]')) {
        const parent = e.target.closest('.list-group-item');
        const inputs = parent.querySelectorAll('input[type="time"]');
        inputs.forEach(input => input.disabled = e.target.checked);
    }
});


    document.getElementById('saveScheduleButton').addEventListener('click', function () {
        const scheduleForm = document.getElementById('scheduleForm');
        const formData = new FormData(scheduleForm);

        const doctorId = scheduleModal.getAttribute('data-doctor-id');

        fetch(`/doctor-schedules/${doctorId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Horários salvos com sucesso!');
                    const modal = bootstrap.Modal.getInstance(scheduleModal);
                    modal.hide();
                } else {
                    alert('Erro ao salvar horários: ' + data.message);
                }
            })
            .catch(() => {
                alert('Erro ao salvar horários. Por favor, tente novamente.');
            });
    });

    document.addEventListener('DOMContentLoaded', function () {
    const doctorSearch = document.getElementById('doctorSearch');

    if (doctorSearch) {
        doctorSearch.addEventListener('input', function () {
            const searchQuery = this.value;

            fetch(`/list?search=${encodeURIComponent(searchQuery)}`)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTableBody = doc.querySelector('#doctorTableBody');
                    const newPagination = doc.querySelector('.pagination');

                    document.querySelector('#doctorTableBody').innerHTML = newTableBody.innerHTML;
                    document.querySelector('.pagination').innerHTML = newPagination.innerHTML;
                })
                .catch(error => {
                    console.error('Erro ao buscar os médicos:', error);
                });
        });
    } else {
        console.error('Elemento com ID "doctorSearch" não encontrado!');
    }
});


document.querySelectorAll('.doctors.toggleStatus').forEach(form => {
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const statusIcon = this.querySelector('.status-icon');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Atualize o ícone e título conforme o novo status
                if (data.status) {
                    // Paciente ativo
                    statusIcon.classList.remove('status-inactive', 'bx-x-circle');
                    statusIcon.classList.add('status-active', 'bx-check-circle');
                    statusIcon.setAttribute('title', 'Ativo - Clique para desativar');
                } else {
                    // Paciente inativo
                    statusIcon.classList.remove('status-active', 'bx-check-circle');
                    statusIcon.classList.add('status-inactive', 'bx-x-circle');
                    statusIcon.setAttribute('title', 'Inativo - Clique para ativar');
                }
            } else {
                console.error('Erro ao alterar o status.');
            }
        })
        .catch(error => console.error('Erro na requisição:', error));
    });
});






document.getElementById('exportButton').addEventListener('click', function () {
            // Localiza a tabela no DOM
            var table = document.querySelector('#doctorTable table');
            if (!table) {
                console.error('Tabela não encontrada!');
                return;
            }

            // Extrai os dados da tabela
            var rows = Array.from(table.rows).map(row =>
                Array.from(row.cells).map(cell => cell.innerText)
            );

            // Cria uma nova planilha e adiciona os dados
            var wb = XLSX.utils.book_new();
            var ws = XLSX.utils.aoa_to_sheet(rows);

            // Adiciona a planilha ao workbook
            XLSX.utils.book_append_sheet(wb, ws, 'Médicos');

            // Exporta o arquivo para o Excel
            XLSX.writeFile(wb, 'medicos.xlsx');
        });





        document.getElementById('novoMedicoBtn').addEventListener('click', function () {
        document.getElementById('novoMedicoModal').style.display = 'flex';
    });

    document.getElementById('closeModal').addEventListener('click', function () {
        document.getElementById('novoMedicoModal').style.display = 'none';
    });

    window.addEventListener('click', function (e) {
        const modal = document.getElementById('novoMedicoModal');
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    document.getElementById('doctorSearch').addEventListener('input', function () {
    const query = this.value.toLowerCase();
    const rows = document.querySelectorAll('#doctorTableBody tr');

    rows.forEach(row => {
        const nameCell = row.querySelector('td:first-child');
        const name = nameCell.textContent.toLowerCase();

        if (name.includes(query)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

</script>
 

@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
