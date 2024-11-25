<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <title>Clinica</title>
</head>

<body class="d-flex align-items-center justify-content-center vh-100 bg-light">
    <div class="card shadow-lg border-0" style="width: 35rem; border-radius: 15px;">
        <main class="form-signin w-100 m-auto p-4">

            @if (session('erro'))
                <div class="alert alert-danger" role="alert">
                    {{session('erro')}}
                </div>     
            @endif

            @if ($errors ->any())
                <div class="alert alert-danger" role="alert">
                    @foreach ($errors->all() as $errors )
                        {{$errors}}<br>
                    @endforeach
                </div>     
            @endif

            

            <form action="{{ route('login.process') }}" method="POST" class="p-4 border rounded shadow bg-light" style="max-width: 400px; margin: auto;">
                <!-- Token CSRF -->
                @csrf
                @method('POST')
            
                <!-- Cabeçalho -->
                <div class="text-center mb-4">
                    <img class="mb-3 rounded-circle shadow-sm" src="{{ asset('imagens/clinica_logo.png') }}" alt="Logo da Clínica" width="100" height="100">
                    <h1 class="h4 mb-2 fw-bold text-primary">Bem-vindo</h1>
                    <p class="text-muted">Inicie sessão para acessar sua conta</p>
                </div> 
            
                <!-- Campo de E-mail -->
                <div class="form-floating mb-3">
                    <input type="email" name="email" class="form-control" id="email" placeholder="E-mail" value="{{ old('email') }}" required>
                    <label for="email">E-mail</label>
                </div>
            
                <!-- Campo de Senha -->
                <div class="form-floating mb-4">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Senha" required>
                    <label for="password">Senha</label>
                </div>
            
                <!-- Botão de Login -->
                <button class="btn btn-primary w-100 py-2 fw-bold" type="submit">Entrar</button>
            
                <!-- Link para Registro -->
                <div class="text-center mt-3">
                    <a href="{{ route('register.show') }}" class="text-decoration-none text-primary small">Não tem uma conta? Registre-se</a>
                </div>
            </form>
            
        </main>
    </div>
</body>
</html>