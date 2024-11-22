<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <title>Clinica</title>
</head>

<body class="d-flex align-items-center justify-content-center vh-100 bg-light">
    <div class="card shadow-lg border-0" style="width: 22rem; border-radius: 15px;">
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

            

            <form action="{{ route('login.process') }}" method="POST">
                <!-- O token CSRF nao recebe de servidor diferente  -->
                @csrf
                @method('POST')
                
                <div class="text-center mb-4">
                    <img class="mb-3" src="{{ asset('imagens/clinica_logo.png') }}" alt="Logo da Clínica" width="100" height="100">
                    <h1 class="h4 mb-2 fw-bold text-primary">Bem-vindo</h1>
                    <p class="text-muted">Iniciar sessão para aceder à sua conta</p>
                </div> 
                 <!-- --> 
        
                 <div class="form-floating mb-3">
                    <input type="email"  name="email" class="form-control"  id="email" placeholder="email" value="{{ old('email')}}">
                    <label for="email">E-mail</label>
                    
                </div>
                <!-- -->

                <!-- --> 
                
                <div class="form-floating mb-3">
                    <input type="password"  name="password" class="form-control"  id="password"  placeholder="Password" >
                    <label for="password">Senha</label>
                    
                </div>
                <!-- -->

                <button class="btn btn-primary w-100 py-2" type="submit">Entrar</button>
                
                <div class="text-center mt-3">
                    <a href="#" class="text-decoration-none text-primary small">Registar-se</a>
                </div>
            </form>
        </main>
    </div>
</body>
</html>