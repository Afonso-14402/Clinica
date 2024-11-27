@extends('layouts.admin')

@section('title', 'Registro - Clínica')

@section('content')
<div class="card shadow-lg border-0 bg-white mx-auto" style="max-width: 36rem;">
    <main class="form-signin w-100 m-auto p-4">
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

            <!-- Header -->
            <div class="text-center mb-4">
                <img class="mb-3 rounded-circle shadow" src="{{ asset('imagens/clinica_logo.png') }}" alt="Logo da Clínica" width="100" height="100">
                <h1 class="h4 mb-2 fw-bold text-primary">Bem-vindo</h1>
                <p class="text-muted">Registre-se para acessar sua conta</p>
            </div>

            <!-- Inputs -->
            <div class="form-floating mb-3">
                <input type="text" name="name" id="name" class="form-control" placeholder="Nome" value="{{ old('name') }}" required>
                <label for="name">Nome</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Sobrenome" value="{{ old('last_name') }}" required>
                <label for="last_name">Sobrenome</label>
            </div>
            <div class="form-floating mb-3">
                <input type="date" name="birth_date" id="birth_date" class="form-control" value="{{ old('birth_date') }}" required>
                <label for="birth_date">Data de Nascimento</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
                <label for="email">E-mail</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" name="password" id="password" class="form-control" placeholder="Senha" required>
                <label for="password">Senha</label>
            </div>
            <div class="form-floating mb-4">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirme a Senha" required>
                <label for="password_confirmation">Confirme a Senha</label>
            </div>

            <!-- Botão -->
            <button class="btn btn-primary w-100 py-2" type="submit">Registrar-se</button>

            <!-- Link para Login -->
            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none text-primary small">Já possui uma conta? Faça login</a>
            </div>
        </form>
    </main>
</div>
@endsection
