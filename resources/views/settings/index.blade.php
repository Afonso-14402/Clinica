@extends('layouts.admin')

@section('content')

     <!-- Layout wrapper -->
     <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
          <!-- Layout container -->
          <div class="layout-page">
              <!-- Navbar -->
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

              <!-- Success Message -->
              @if(session('success'))
              <div class="toast-container position-fixed bottom-0 end-0 p-3">
                  <div class="toast bg-success">
                      <div class="toast-header">
                          <i class="bx bx-bell me-2"></i>
                          <div class="me-auto fw-medium">Sucesso</div>
                          <small>Agora</small>
                          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                      </div>
                      <div class="toast-body">
                          {{ session('success') }}
                      </div>
                  </div>
              </div>
              @endif                

              <!-- Content wrapper -->
              <div class="content-wrapper">
                  <div class="container py-4">
                      <div class="row">
                          <div class="col-lg-4">
                              <!-- Profile Section -->
                              <div class="card">
                                  <div class="card-body text-center">
                                      <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}" alt="avatar" class="rounded-circle img-fluid" style="width: 120px;">
                                      <h5 class="my-3">{{ $user->name }}</h5>
                                      <p class="text-muted mb-4">{{ $user->role->role ?? 'Sem função atribuída' }}</p>  
                                      <form method="POST" action="{{ route('user.update.avatar') }}" enctype="multipart/form-data">
                                          @csrf
                                          <div class="mb-3">
                                              <input type="file" name="avatar" class="form-control" accept="image/png, image/jpeg">
                                          </div>
                                          <small class="form-text" style="color: #888;">Formatos permitidos: JPG, PNG. Tamanho máximo: 800KB.</small>
                                          <button type="submit" class="btn btn-primary btn-sm">Carregar imagem</button>
                                      </form>
                                  </div>
                              </div>
                          </div>
                          <div class="col-lg-8">
                              <!-- Account Settings -->
                              <div class="card">
                                  <div class="card-header">
                                      <h5 class="mb-0">Account Settings</h5>
                                  </div>
                                  <div class="card-body">
                                      <form method="POST" id="formAccountSettings">
                                          <div class="row mb-3">
                                              <label for="firstName" class="form-label">Nome</label>
                                              <input type="text" class="form-control" id="firstName" name="firstName" value="{{ $user->name }}" readonly>
                                          </div>
                                          <div class="row mb-3">
                                              <label for="email" class="form-label">E-mail</label>
                                              <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" readonly>
                                          </div>
                                      </form>
                                  </div>
                              </div>
                              <div class="card mt-4">
                                  <div class="card-header">
                                      <h5 class="mb-0">Alterar Senha</h5>
                                  </div>
                                  <div class="card-body">
                                      <form method="POST" action="{{ route('user.change-password') }}">
                                          @csrf
                                          <div class="mb-3">
                                            <label for="current_password" class="form-label">Senha Atual</label>
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
                                        <div class="mb-3">
                                            <label for="new_password" class="form-label">Nova Senha</label>
                                            <div class="input-group input-group-merge">
                                                <input
                                                    type="password"
                                                    id="new_password"
                                                    class="form-control"
                                                    name="new_password"
                                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                    aria-describedby="password" />
                                                <span class="input-group-text cursor-pointer toggle-password">
                                                    <i class="bx bx-hide"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="new_password_confirmation" class="form-label">Confirme a Nova Senha</label>
                                            <div class="input-group input-group-merge">
                                                <input
                                                    type="password"
                                                    id="new_password_confirmation"
                                                    class="form-control"
                                                    name="new_password_confirmation"
                                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                    aria-describedby="password" />
                                                <span class="input-group-text cursor-pointer toggle-password">
                                                    <i class="bx bx-hide"></i>
                                                </span>
                                            </div>
                                        </div>
                                          <button type="submit" class="btn btn-primary">Alterar Senha</button>
                                          <button type="reset" class="btn btn-outline-secondary me-2">Cancelar</button>
                                      </form>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
@endsection


