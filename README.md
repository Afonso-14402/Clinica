# APClinica - Sistema de Gestão de Clínica Médica

## Requisitos do Sistema

- **PHP**: 8.2 ou superior
- **Composer**
- **MySQL**: 5.7 ou superior
- **Node.js** e **NPM**

## Instalação Passo a Passo

1. **Instale com o Composer:**
   ```bash
   composer install
   ```
   > **Aviso:** Se ocorrer algum erro, é necessário desativar o Windows Defender.

2. **Instale as dependências do Node.js:**
   ```bash
   npm install
   ```

3. **Instale o Bootstrap e o Popper.js:**
   ```bash
   npm i --save bootstrap @popperjs/core
   ```

4. **Instale o SASS:**
   ```bash
   npm i --save-dev sass
   ```

5. **Instale o Highlight.js para formatação de código:**
   ```bash
   npm install highlight.js
   ```

Para acessar imagens armazenadas na pasta de storage no Laravel, você pode usar o seguinte comando:

```bash
php artisan storage:link
```

6. **Copie o arquivo de ambiente:**
   ```bash
   cp .env.example e mude o nome para .env
   ```

7. **Configure o arquivo .env com as suas credenciais de base de dados:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=apclinica
   DB_USERNAME=root
   DB_PASSWORD=
   ```

8. **Gere a chave da aplicação:**
   ```bash
   php artisan key:generate
   ```

9. **Crie a base de dados:**
   Vá ao phpMyAdmin e crie a base de dados `apclinica`:
   ```sql
   CREATE DATABASE apclinica;
   ```

9.1. **Importe o arquivo `apclinica.sql` para o phpMyAdmin da tabela `apclinica`.**

## Iniciar o Servidor de Desenvolvimento

Execute os seguintes comandos em terminais separados:

```bash
npm run dev
php artisan serve
```

## Utilizadores Padrão

Após executar os seeders, os seguintes utilizadores estarão disponíveis:

- **Administrador:**
  - Email: `admin@gmail.com`
  - Senha: `123456789`

- **Médico:**
  - Email: `doctor@gmail.com`
  - Senha: `123456789`

- **Paciente:**
  - Email: `paciente@gmail.com`
  - Senha: `123456789`
,
## Estrutura da Base de Dados

O sistema utiliza as seguintes tabelas principais:

- `users`: Armazena todos os utilizadores do sistema
- `roles`: Define os papéis dos utilizadores (Admin, Médico, Paciente)
- `specialties`: Especialidades médicas disponíveis
- `appointments`: Consultas médicas
- `reports`: Relatórios das consultas
- `activity_log`: Registo de atividades do sistema
- `dados_pessoais`: Informações pessoais dos pacientes
- `family_doctor`: Relacionamento médico de família com pacientes
- `user_doctor_agenda`: Agenda dos médicos
- `user_specialties`: Especialidades de cada médico
- `status`: Status possíveis para as consultas

## Funcionalidades Principais

- Gestão de utilizadores (admin, médicos e pacientes)
- Agendamento de consultas
- Registo de relatórios médicos
- Gestão de médicos de família
- Registo de dados pessoais dos pacientes
- Log de atividades do sistema

## Suporte

Para suporte, entre em contacto através do email: [a14402@oficina.pt](mailto:a14402@oficina.pt)

