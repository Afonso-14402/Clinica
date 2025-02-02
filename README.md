# APClinica - Sistema de Gestão de Clínica Médica

## Requisitos do Sistema

- PHP 8.2 ou superior
- Composer
- MySQL 5.7 ou superior
- Node.js e NPM

## Instalação Passo a Passo

1. Crie um novo projeto Laravel:
```
composer create-project laravel/laravel .
```
```
composer install
```
2. Instale as dependências do Node.js:
```
npm install
```

3. Instale o Bootstrap e Popper.js:
```
npm i --save bootstrap @popperjs/core
```

4. Instale o SASS:
```
npm i --save-dev sass
```

5. Instale o Highlight.js para formatação de código:
```
npm install highlight.js
```

6. Copie o arquivo de ambiente:
```
cp .env.example  e mode o nome para  .env
```

7. Configure o arquivo .env com suas credenciais de banco de dados:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apclinica
DB_USERNAME=root
DB_PASSWORD=
```

8. Gere a chave da aplicação:
```
php artisan key:generate
```

9. Crie o banco de dados:
```
CREATE DATABASE apclinica;

php artisan migrate --path=/database/migrations/--------------------

```

## Ordem de Execução das Migrações

As migrações devem ser executadas na seguinte ordem devido às dependências:

1. 2024_12_04_150113_create_roles_table.php
  - php artisan migrate --path=/database/migrations/2024_12_04_150113_create_roles_table.php 

2. 2024_12_04_150227_create_specialties_table.php
  - php artisan migrate --path=/database/migrations/2024_12_04_150227_create_specialties_table.php

3. 0001_01_01_000000_create_users_table.php
  - php artisan migrate --path=/database/migrations/0001_01_01_000000_create_users_table.php

4. 2024_12_04_150234_create_user_specialties_table.php
  - php artisan migrate --path=/database/migrations/2024_12_04_150234_create_user_specialties_table.php
   
5. 2024_12_04_150237_create_user_doctor_agenda_table.php
  - php artisan migrate --path=/database/migrations/2024_12_04_150237_create_user_doctor_agenda_table.php

6. 2024_12_04_150251_create_status_table.php
  - php artisan migrate --path=/database/migrations/2024_12_04_150251_create_status_table.php

7. 2024_12_04_150240_create_appointments_table.php
  - php artisan migrate --path=/database/migrations/2024_12_04_150240_create_appointments_table.php

8. 2024_12_04_150244_create_reports_table.php
  - php artisan migrate --path=/database/migrations/2024_12_04_150244_create_reports_table.php

9. 2024_12_25_155943_create_activity_log_table.php
  - php artisan migrate --path=/database/migrations/2024_12_25_155943_create_activity_log_table.php

10. 2025_01_03_202316_create_family_doctor_table.php
 - php artisan migrate --path=/database/migrations/2025_01_03_202316_create_family_doctor_table.php

11. 2025_01_25_000000_create_dados_pessoais_table.php
 - php artisan migrate --path=/database/migrations/2025_01_25_000000_create_dados_pessoais_table.php


10. Execute as migrações e seeders:
```
php artisan migrate --seed
```

## Iniciar o Servidor de Desenvolvimento

Execute os seguintes comandos em terminais separados:

```
npm run dev
php artisan serve
```

## Configuração do Git

1. Inicialize o repositório:
```
git init
```

2. Configure suas credenciais do Git:
```
git config --global user.name "Seu-Usuario"
git config --global user.email "seu-email@exemplo.com"
```

3. Adicione os arquivos ao repositório:
```
git add .
```

## Usuários Padrão

Após executar os seeders, os seguintes usuários estarão disponíveis:

- Administrador:
  - Email: admin@example.com
  - Senha: password

- Médico:
  - Email: doctor@example.com
  - Senha: password

## Estrutura do Banco de Dados

O sistema utiliza as seguintes tabelas principais:

- `users`: Armazena todos os usuários do sistema
- `roles`: Define os papéis dos usuários (Admin, Doctor, Patient)
- `specialties`: Especialidades médicas disponíveis
- `appointments`: Consultas médicas
- `reports`: Relatórios das consultas
- `activity_log`: Registro de atividades do sistema
- `dados_pessoais`: Informações pessoais dos pacientes
- `family_doctor`: Relacionamento médico de família com pacientes
- `user_doctor_agenda`: Agenda dos médicos
- `user_specialties`: Especialidades de cada médico
- `status`: Status possíveis para as consultas



## Funcionalidades Principais

- Gestão de usuários (admin, médicos e pacientes)
- Agendamento de consultas
- Registro de relatórios médicos
- Gestão de médicos de família
- Registro de dados pessoais dos pacientes
- Log de atividades do sistema

## Comandos Úteis

Verificar migração específica:
```sql
SELECT * FROM migrations WHERE migration = '2024_12_08_114930_add_avatar_to_users_table';
```

## Suporte

Para suporte, entre em contato através do email: afonsomanuelcoelhopacheco@gmail.com 