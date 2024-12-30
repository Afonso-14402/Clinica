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
            
            Lista de Pacientes
            
            <div class="col-md-4">
                <input type="text" id="patientSearch" class="form-control" placeholder="Pesquisar por nome...">   
            </div>

            <button id="exportButton" class="btn btn-success btn-sm">
                <i class="bx bx-download"></i> Exportar para Excel
            </button>

            <button id="openModal" class="btn btn-primary">Novo paciente </button>
            
            
        </h5>
        <div class="table-responsive text-nowrap" id="doctorTable">
            <table class="table">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="patientTableBody">
                    @foreach($patients as $patient)
                    <tr>
                        <td>{{ $patient->id }}</td>
                        <td>{{ $patient->name }}</td>
                        <td>{{ $patient->email }}</td>
                        
                        
                        <!-- Status do paciente   -->
                        <td>
                            <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" style="border: none; background: none; padding: 0;">
                                    <i class="status-icon {{ $patient->status ? 'status-active bx bx-check-circle' : 'status-inactive bx bx-x-circle' }}" data-toggle="tooltip" title="{{ $patient->status ? 'Ativo - Clique para desativar' : 'Inativo - Clique para ativar' }}"></i>
                                </button>
                            </form>
                        </td>
                        
                        <!-- Ações -->
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Ações
                                </button>
                                <ul class="dropdown-menu">
                                    <!-- Editar Paciente -->
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-edit-alt me-1"></i> Editar
                                        </a>
                                    </li>
                                    
                                    <!-- Gerenciar Agendamentos -->
                                    <li>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPacienteModal">
                                            Adicionar Paciente
                                          </button>
                                          
                                    </li>
                                    
                                    
                                    <!-- Excluir Paciente -->
                                    <li>
                                        <form  action="{{ route('patients.destroy', $patient->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este paciente?');">
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
                    {{-- Link para a página anterior --}}
                    @if ($patients->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">Anterior</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $patients->previousPageUrl() }}">Anterior</a>
                        </li>
                    @endif
                
                    {{-- Links para as páginas --}}
                    @for ($i = 1; $i <= $patients->lastPage(); $i++)
                        <li class="page-item {{ $patients->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $patients->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                
                    {{-- Link para a próxima página --}}
                    @if ($patients->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $patients->nextPageUrl() }}">Próxima</a>
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
   <!-- Modal para medicos -->
   <div class="modal" id="novopacienteModal" tabindex="-1" style="display: none;"> 
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Paciente</h5>
                <button type="button" class="btn-close" id="closeModal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulário de registro do médico -->
                <form action="{{ route('registar.paciente') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <!-- Nome e Sobrenome -->
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Sobrenome</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <!-- E-mail e Data de Nascimento -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="col-md-6">
                            <label for="birth_date" class="form-label">Data de Nascimento</label>
                            <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                        </div>
                    </div>

                    <!-- Senhas -->
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
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


<!-- Modal -->
<div class="modal fade" id="addPacienteModal" tabindex="-1" aria-labelledby="addPacienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addPacienteModalLabel">Adicionar Paciente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Formulário com abas -->
          <div class="card shadow-sm">
            <div class="card-header">
              <ul class="nav nav-tabs card-header-tabs" id="pacienteModalTab" role="tablist">
                @foreach (['Dados de Acesso', 'Dados Pessoais', 'Dados Complementares', 'Convênios', 'Prontuário Médico'] as $index => $tab)
                <li class="nav-item" role="presentation">
                  <button 
                    class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                    id="modal-{{ Str::slug($tab) }}-tab" 
                    data-bs-toggle="tab" 
                    data-bs-target="#modal-{{ Str::slug($tab) }}" 
                    type="button" 
                    role="tab">
                    {{ $tab }}
                  </button>
                </li>
                @endforeach
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content" id="pacienteModalTabContent">
                <!-- Aba: Dados de Acesso -->
                <div class="tab-pane fade show active" id="modal-dados-de-acesso" role="tabpanel">
                  <form id="formDadosAcesso">
                    <div class="mb-3">
                      <label for="modal-email" class="form-label">E-mail</label>
                      <input type="email" class="form-control" id="modal-email" name="email" placeholder="Digite o e-mail do paciente" required>
                    </div>
                    <div class="mb-3">
                      <label for="modal-senha" class="form-label">Senha</label>
                      <input type="password" class="form-control" id="modal-senha" name="senha" placeholder="Defina uma senha" required>
                    </div>
                  </form>
                </div>
  
                <!-- Aba: Dados Pessoais -->
                <div class="tab-pane fade" id="modal-dados-pessoais" role="tabpanel">
                  <form id="formDadosPessoais">
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="modal-nome" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="modal-nome" name="nome" placeholder="Digite o nome completo" required>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="modal-data-nascimento" class="form-label">Data de Nascimento</label>
                        <input type="date" class="form-control" id="modal-data-nascimento" name="data_nascimento" required>
                      </div>
                    </div>
                    <div class="mb-3">
                      <label for="modal-telefone" class="form-label">Telefone</label>
                      <input type="tel" class="form-control" id="modal-telefone" name="telefone" placeholder="(XX) XXXXX-XXXX">
                    </div>
                  </form>
                </div>
  
                <!-- Aba: Dados Complementares -->
                <div class="tab-pane fade" id="modal-dados-complementares" role="tabpanel">
                  <form id="formDadosComplementares">
                    <div class="mb-3">
                      <label for="modal-endereco" class="form-label">Endereço</label>
                      <input type="text" class="form-control" id="modal-endereco" name="endereco" placeholder="Digite o endereço completo">
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="modal-cidade" class="form-label">Cidade</label>
                        <input type="text" class="form-control" id="modal-cidade" name="cidade">
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="modal-estado" class="form-label">Estado</label>
                        <input type="text" class="form-control" id="modal-estado" name="estado">
                      </div>
                    </div>
                  </form>
                </div>
  
                <!-- Aba: Convênios -->
                <div class="tab-pane fade" id="modal-convenios" role="tabpanel">
                  <form id="formConvenios">
                    <div class="mb-3">
                      <label for="modal-convenio" class="form-label">Convênio</label>
                      <input type="text" class="form-control" id="modal-convenio" name="convenio" placeholder="Informe o convênio do paciente">
                    </div>
                    <div class="mb-3">
                      <label for="modal-numero-carteira" class="form-label">Número da Carteira</label>
                      <input type="text" class="form-control" id="modal-numero-carteira" name="numero_carteira">
                    </div>
                  </form>
                </div>
  
                <!-- Aba: Prontuário Médico -->
                <div class="tab-pane fade" id="modal-prontuario-medico" role="tabpanel">
                  <p>Cadastro de Prontuário</p>
                  <p>O cadastro da Anamnese, exame físico e/ou Hipótese diagnóstica será armazenado como ficha inicial do paciente.</p>
                  <button type="button" class="btn btn-success">Iniciar Consulta</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="salvarPaciente">Salvar</button>
        </div>
      </div>
    </div>
  </div>
  
<script>
    
    document.addEventListener('DOMContentLoaded', function () {
    const patientSearch = document.getElementById('patientSearch');

    if (patientSearch) {
        patientSearch.addEventListener('input', function () {
            const searchQuery = this.value;

            fetch(`/list/patients?search=${encodeURIComponent(searchQuery)}`)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Verifica se os elementos existem no HTML retornado
                    const newTableBody = doc.querySelector('#patientTableBody');
                    const newPagination = doc.querySelector('.pagination');

                    if (newTableBody && newPagination) {
                        document.querySelector('#patientTableBody').innerHTML = newTableBody.innerHTML;
                        document.querySelector('.pagination').innerHTML = newPagination.innerHTML;
                    } else {
                        console.warn('Não foram encontrados os elementos esperados no HTML retornado.');
                        console.warn('Verifique se o servidor está retornando o HTML correto.');
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar os pacientes:', error);
                });
        });
    } else {
        console.error('Elemento com ID "patientSearch" não encontrado!');
    }
});



document.querySelectorAll('.toggle-status-form').forEach(form => {
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
    // Localiza a tabela de pacientes no DOM
    var table = document.querySelector('#patientTable table');
    if (!table) {
        console.error('Tabela não encontrada!');
        return;
    }

    // Extrai os dados da tabela (linhas e células)
    var rows = Array.from(table.rows).map(row =>
        Array.from(row.cells).map(cell => cell.innerText.trim())  // .trim() para remover espaços extras
    );

    // Cria uma nova planilha e adiciona os dados
    var wb = XLSX.utils.book_new();
    var ws = XLSX.utils.aoa_to_sheet(rows);

    // Adiciona a planilha ao workbook
    XLSX.utils.book_append_sheet(wb, ws, 'Pacientes');

    // Exporta o arquivo para o Excel
    XLSX.writeFile(wb, 'pacientes.xlsx');
});


document.addEventListener('DOMContentLoaded', function () {
    // Abrir modal
    document.getElementById('openModal').addEventListener('click', function () {
        document.getElementById('novopacienteModal').style.display = 'block';
    });

    // Fechar modal pelo botão de fechar
    document.getElementById('closeModal').addEventListener('click', function () {
        document.getElementById('novopacienteModal').style.display = 'none';
    });

    // Fechar modal clicando fora
    window.addEventListener('click', function (e) {
        const modal = document.getElementById('novopacienteModal');
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Seleciona todos os botões de deletar
    const deleteButtons = document.querySelectorAll('.delete-patient');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const patientId = this.getAttribute('data-id');

            // Confirmação PDA
            if (!confirm(`Tem certeza de que deseja deletar o paciente com ID ${patientId}?`)) {
                return;
            }

            // Envia requisição DELETE
            fetch(`/list/patients/${patientId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (response.ok) {
                        alert('Paciente deletado com sucesso!');
                        // Remova a linha da tabela ou atualize a página
                        this.closest('tr').remove();
                    } else {
                        return response.json().then(err => {
                            throw new Error(err.error || 'Erro ao deletar o paciente.');
                        });
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Houve um erro ao tentar deletar o paciente.');
                });
        });
    });
});

</script>
 

@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
