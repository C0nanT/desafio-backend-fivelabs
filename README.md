# Laravel CRUD - FiveLabs

Aplicação Laravel com operações CRUD básicas utilizando Docker, Nginx e PostgreSQL.

## Requisitos

- [Docker](https://www.docker.com/get-started)

## Configuração Inicial


### 1. Configurar arquivo de ambiente

Copie o arquivo `.env.example` para `.env`:

```bash
cp .env.example .env
```

### 2. Iniciar os containers Docker

```bash
docker-compose up -d
```

### 3. Instalar dependências do Composer

```bash
docker-compose exec app composer install
```

### 4. Gerar chave da aplicação

```bash
docker-compose exec app php artisan key:generate
```

### 5. Executar migrações do banco de dados

```bash
docker-compose exec app php artisan migrate
```

### 6. Executar seeders para dados iniciais

```bash
docker-compose exec app php artisan db:seed
```

## Acessando a Aplicação

Após completar a configuração, acesse a aplicação em:

```
http://localhost:8000
```