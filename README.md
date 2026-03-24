<img width="2545" height="1296" alt="Captura de tela 2026-03-24 131650" src="https://github.com/user-attachments/assets/1f8f3438-c43f-4178-9a70-206669a7ff76" />
<img width="1253" height="1290" alt="Captura de tela 2026-03-24 135313" src="https://github.com/user-attachments/assets/0b5f886e-75d8-4fdb-acf5-1eb75ebe4449" />
<img width="2559" height="445" alt="image" src="https://github.com/user-attachments/assets/6eca09a9-d710-4e72-a754-70c7455345cd" />

# Como rodar o projeto Fenix localmente

Este documento explica como subir o projeto em ambiente local com Docker, Laravel, PostgreSQL, Redis e Vue.

## Estrutura do projeto

- `fenix-api`: backend em Laravel
- `fenix-web`: front-end em Vue 3 com Vite
- `docker-compose.yml`: orquestra os containers
- `dockerfile`: imagem do backend

## Pré-requisitos

- Docker e Docker Compose instalados
- Node.js compatível com o projeto do front
- Composer instalado, caso queira rodar o backend fora do Docker
- Git instalado

## Observação importante

No repositório, o arquivo aparece como `dockerfile` em minúsculo, mas o `docker-compose.yml` pode estar apontando para `Dockerfile` com D maiúsculo.

Em Linux isso quebra. Deixe os dois nomes iguais.

Também existe o volume:

```yml
volumes:
  - ./fenix-api:/var/www
```

Isso pode sobrescrever arquivos instalados no build. Por isso, depois que o container subir, rode `composer install` dentro dele.

## Passo a passo para subir tudo

### 1. Clonar o repositório

```bash
git clone https://github.com/ricichien/fenix.git
cd fenix
```

### 2. Garantir que o Dockerfile está com o nome correto

Se necessário, renomeie o arquivo para `Dockerfile` ou ajuste o compose para o nome real do arquivo.

### 3. Subir os containers

<img width="2247" height="261" alt="image" src="https://github.com/user-attachments/assets/d568e60e-16b5-48ee-85e1-4188fc07d9f4" />


```bash
docker compose up -d --build
```

### 4. Entrar no container do Laravel

```bash
docker compose exec app bash (ou docker exec -it fenix_app bash)
```

### 5. Instalar dependências do backend

```bash
composer install
```

### 6. Criar o arquivo `.env`

```bash
cp .env.example .env
```

### 7. Gerar a chave da aplicação

```bash
php artisan key:generate
```

### 8. Configurar o banco no `.env`

```env
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=fenix
DB_USERNAME=fenix
DB_PASSWORD=fenix

REDIS_HOST=redis
QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database
```

### 9. Rodar migrations

```bash
php artisan migrate --force
```

### 10. Rodar seeders, se existirem

```bash
php artisan db:seed --force
```

## Como rodar o front-end Vue

Em outro terminal, entre na pasta do front:

```bash
cd fenix-web
```

Instale as dependências:

```bash
npm install
```

Inicie o ambiente de desenvolvimento:

```bash
npm run dev
```

O front usa Vite, então normalmente ficará disponível em uma porta como `5173`.

Verifique se a URL da API está apontando para o backend correto no código do projeto.

## Fluxo completo resumido

```bash
# subir containers
cd fenix
docker compose up -d --build

# preparar backend
docker compose exec app bash (ou docker exec -it fenix_app bash)
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --force

# rodar front
cd fenix-web
npm install
npm run dev
```

## Problemas comuns

- **Dockerfile com nome errado**: padronize o nome no compose e no repositório.
- **vendor sumiu**: rode `composer install` dentro do container após subir o volume.
- **Banco não conecta**: verifique se o `DB_HOST` está como `postgres`, não `localhost`.
- **Front não chama API**: revise a URL base do axios e o CORS.
- **Migrations falham**: confirme se o Postgres já está pronto antes de rodar o migrate.

## Observação final

Se quiser deixar isso mais profissional, o ideal é separar:
- execução local
- execução via Docker
- execução do front
- execução dos testes
