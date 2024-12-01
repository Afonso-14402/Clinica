@extends('layouts.admin')

@section('title', 'Registro - Clínica')

@section('content')
<div class="container">
    <main>
        <div class="py-5 text-center">
            <h2 class="fw-bold">Registro - APclínica</h2>
            <p class="lead">Preencha as informações abaixo para completar seu registro.</p>
        </div>

        <div class="row g-5">
            <div class="col-md-8 col-lg-7 mx-auto">
              <form action="{{ route('register.store') }}" method="POST" class="p-4">
                  <!-- CSRF Token -->
                  @csrf
                  <!-- Error Messages -->
                  @if ($errors->any())
                  <div class="alert alert-danger">
                      <ul class="mb-0">
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
                  @endif
                    <h4 class="mb-3">Informações do Pacinete</h4>
                    <form class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-sm-6">
                              <div class="form-floating mb-3">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Nome" value="{{ old('name') }}" required>
                                <label for="name">Nome</label>
                              </div>
                            </div>

                            <div class="col-sm-6">
                              <div class="form-floating mb-3">
                                <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Sobrenome" value="{{ old('last_name') }}" required>
                                <label for="last_name">Sobrenome</label>
                              </div>
                            </div>

                            <div class="col-12">
                              <div class="form-floating mb-3">
                                <input type="date" name="birth_date" id="birth_date" class="form-control" value="{{ old('birth_date') }}" required>
                                <label for="birth_date">Data de Nascimento</label>
                              </div>
                            </div>

                            <div class="col-12">
                              <div class="form-floating mb-3">
                                <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
                                <label for="email">E-mail</label>
                              </div>
                            </div>

                            <div class="col-12">
                              <div class="form-floating mb-3">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Senha" required>
                                <label for="password">Senha</label>
                              </div>
                            </div>

                            <div class="col-12">
                              <div class="form-floating mb-4">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirme a Senha" required>
                                <label for="password_confirmation">Confirme a Senha</label>
                              </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <button class="btn btn-primary w-100 py-2" type="submit">Registrar</button>
                    </form>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection
