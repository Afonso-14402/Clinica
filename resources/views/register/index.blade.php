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
  <div class="card shadow-lg border-0" style="width: 36rem; border-radius: 15px;">
      <main class="form-signin w-100 m-auto p-4">
        <form action="{{ route('register.store') }}" method="POST" class="p-4 border rounded shadow-sm bg-light">
          <!-- Token CSRF -->
          @csrf
          @method('POST')
      
          <!-- Mensagens de Erro -->
          @if ($errors->any())
          <div class="alert alert-danger">
              <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
          @endif
      
          <!-- Cabeçalho -->
          <div class="text-center mb-4">
              <img class="mb-3 rounded-circle shadow" src="{{ asset('imagens/clinica_logo.png') }}" alt="Logo da Clínica" width="100" height="100">
              <h1 class="h4 mb-2 fw-bold text-primary">Bem-vindo</h1>
              <p class="text-muted">Registre-se para acessar sua conta</p>
          </div> 
      
          <!-- Nome -->
          <div class="form-floating mb-3">
              <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Nome" required>
              <label for="name">Nome</label>
              @if ($errors->has('name'))
                  <span class="text-danger small">{{ $errors->first('name') }}</span>
              @endif
          </div>
      
          <!-- Sobrenome -->
          <div class="form-floating mb-3">
              <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name') }}" placeholder="Sobrenome" required>
              <label for="last_name">Sobrenome</label>
              @if ($errors->has('last_name'))
                  <span class="text-danger small">{{ $errors->first('last_name') }}</span>
              @endif
          </div>
      
          <!-- Data de Nascimento -->
          <div class="form-floating mb-3">
              <input type="date" name="birth_date" id="birth_date" class="form-control" value="{{ old('birth_date') }}" required>
              <label for="birth_date">Data de Nascimento</label>
          </div>
      
          <!-- Email -->
          <div class="form-floating mb-3">
              <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
              <label for="email">E-mail</label>
              @if ($errors->has('email'))
                  <span class="text-danger small">{{ $errors->first('email') }}</span>
              @endif
          </div>
      
          <!-- Senha -->
          <div class="form-floating mb-3">
              <input type="password" name="password" id="password" class="form-control" placeholder="Senha" required>
              <label for="password">Senha</label>
              @if ($errors->has('password'))
                  <span class="text-danger small">{{ $errors->first('password') }}</span>
              @endif
          </div>
      
          <!-- Confirmação de Senha -->
          <div class="form-floating mb-4">
              <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirme a Senha" required>
              <label for="password_confirmation">Confirme a Senha</label>
          </div>
      
          <!-- Botão de Registro -->
          <button class="btn btn-primary w-100 py-2" type="submit">Registrar-se</button>
      
          <!-- Link para Login -->
          <div class="text-center mt-3">
              <a href="{{ route('login') }}" class="text-decoration-none text-primary small">Já possui uma conta? Faça login</a>
          </div>
      </form>
      
      </main>
  </div>
</body>
</html>