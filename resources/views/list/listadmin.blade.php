@extends('layouts.admin')
@section('title', 'Contas')

<style>
// ... manter os mesmos estilos existentes ...
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
            Contas

            <div class="col-md-4">
                <input type="text" id="adminSearch" class="form-control" placeholder="Pesquisar por nome...">
            </div>

            <button id="exportButton" class="btn btn-success btn-sm">
                <i class="bx bx-download"></i> Exportar para Excel
            </button>

            <button id="openModal" class="btn btn-primary">Novo Administrador</button>
        </h5>
        
        <div class="table-responsive text-nowrap" id="adminTable">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="adminTableBody">
                    @foreach($admins as $admin)
                    <tr>
                        <td>{{ $admin->id }}</td>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>
                            <form action="{{ route('admin.toggle-status', $admin->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" style="border: none; background: none; padding: 0;">
                                    <i class="status-icon {{ $admin->status ? 'status-active bx bx-check-circle' : 'status-inactive bx bx-x-circle' }}" 
                                       data-toggle="tooltip" 
                                       title="{{ $admin->status ? 'Ativo - Clique para desativar' : 'Inativo - Clique para ativar' }}">
                                    </i>
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
                                        <a class="dropdown-item" href="javascript:void(0);" 
                                           data-bs-toggle="modal" 
                                           data-bs-target="#editAdminModal" 
                                           data-admin-id="{{ $admin->id }}">
                                            <i class="bx bx-edit-alt me-1"></i> Editar
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.destroy', $admin->id) }}" method="POST" 
                                              onsubmit="return confirm('Tem certeza que deseja eliminar este administrador?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bx bx-trash me-1"></i> Eliminar
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
                    @if ($admins instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{-- Link para a página anterior --}}
                        @if ($admins->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Anterior</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $admins->previousPageUrl() }}">Anterior</a>
                            </li>
                        @endif
                    
                        {{-- Links para as páginas --}}
                        @for ($i = 1; $i <= $admins->lastPage(); $i++)
                            <li class="page-item {{ $admins->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ $admins->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                    
                        {{-- Link para a próxima página --}}
                        @if ($admins->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $admins->nextPageUrl() }}">Próxima</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">Próxima</span>
                            </li>
                        @endif
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Modal para Novo Administrador -->
<div class="modal" id="novoadminModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-user-plus me-2"></i>Registar Novo Administrador
                </h5>
                <button type="button" class="btn-close" id="closeModal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-white">
                <form action="{{ route('admin.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-4">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-check me-1"></i>Registar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Administrador -->
<div class="modal fade" id="editAdminModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-edit-alt me-2"></i>Editar Administrador
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-white">
                <form id="editAdminForm" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="editName" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editPassword" class="form-label">Nova Senha (opcional)</label>
                            <input type="password" class="form-control" id="editPassword" name="password">
                        </div>
                        <div class="col-md-6">
                            <label for="editPasswordConfirmation" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" id="editPasswordConfirmation" name="password_confirmation">
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-4">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-check me-1"></i>Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<script>
// Gestão do modal para adicionar novo administrador
document.addEventListener('DOMContentLoaded', function() {
    // Modal
    document.getElementById('openModal')?.addEventListener('click', () => {
        document.getElementById('novoadminModal').style.display = 'block';
    });

    document.getElementById('closeModal')?.addEventListener('click', () => {
        document.getElementById('novoadminModal').style.display = 'none';
    });

    // Pesquisa em tempo real
    const adminSearch = document.getElementById('adminSearch');
    if (adminSearch) {
        let searchTimeout;
        
        adminSearch.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchQuery = this.value;
            
            searchTimeout = setTimeout(() => {
            fetch(`/admin/search?query=${encodeURIComponent(searchQuery)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro na resposta do servidor');
                        }
                        return response.text();
                    })
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTableBody = doc.querySelector('#adminTableBody');
                        const newPagination = doc.querySelector('.pagination');
                        
                    if (newTableBody) {
                        document.querySelector('#adminTableBody').innerHTML = newTableBody.innerHTML;
                    }
                        if (newPagination) {
                            document.querySelector('.pagination').innerHTML = newPagination.innerHTML;
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao buscar administradores:', error);
                    });
            }, 300); // Delay para evitar muitas requisições
        });
    }

    // Exportar para Excel
    document.getElementById('exportButton')?.addEventListener('click', function() {
        const table = document.querySelector('#adminTable table');
        if (!table) return;

        const rows = Array.from(table.rows).map(row =>
            Array.from(row.cells).map(cell => {
                let clone = cell.cloneNode(true);
                let buttons = clone.querySelectorAll('button, .bx');
                buttons.forEach(btn => btn.remove());
                return clone.innerText.trim();
            })
        );

        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet(rows);
        XLSX.utils.book_append_sheet(wb, ws, 'Administradores');
        XLSX.writeFile(wb, 'lista_administradores.xlsx');
    });

    // Formulário de novo administrador
    const form = document.querySelector('#novoadminModal form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    document.getElementById('novoadminModal').style.display = 'none';
                    window.location.reload();
                } else {
                    let errorMessage = data.message;
                    if (data.errors) {
                        errorMessage = Object.values(data.errors).join('\n');
                    }
                    alert('Erro ao registrar administrador: ' + errorMessage);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao registrar administrador. Por favor, tente novamente.');
            });
        });
    }

    // Edição de administrador
    $(document).on('click', '[data-bs-target="#editAdminModal"]', function() {
        const adminId = $(this).data('admin-id');
        
        // Busca os dados do administrador
        $.ajax({
            url: `/admin/${adminId}/edit`,
            method: 'GET',
            success: function(admin) {
                $('#editAdminForm').attr('action', `/admin/${adminId}`);
                $('#editName').val(admin.name);
                $('#editEmail').val(admin.email);
                
                // Limpa os campos de senha
                $('#editPassword').val('');
                $('#editPasswordConfirmation').val('');
                
                // Abre o modal usando o Bootstrap
                new bootstrap.Modal(document.getElementById('editAdminModal')).show();
            },
            error: function(xhr) {
                alert('Erro ao carregar dados do administrador');
            }
        });
    });

    // Atualização de administrador
    $('#editAdminForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        
        $.ajax({
            url: url,
            type: 'PUT',
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $('#editAdminModal').modal('hide');
                    window.location.reload();
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON.errors;
                let errorMessage = 'Ocorreram os seguintes erros:\n';
                for (let field in errors) {
                    errorMessage += `${errors[field]}\n`;
                }
                alert(errorMessage);
            }
        });
    });

    // Toggle Status
    $(document).on('submit', '.toggle-status-form', function(e) {
        e.preventDefault();
        const form = $(this);
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    const icon = form.find('.status-icon');
                    icon.toggleClass('status-active status-inactive bx-check-circle bx-x-circle');
                    icon.attr('title', response.status ? 'Ativo - Clique para desativar' : 'Inativo - Clique para ativar');
                }
            }
        });
    });
});
</script>
