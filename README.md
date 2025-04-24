# Laravel CRUD - FiveLabs

Aplicação Laravel com operações CRUD básicas utilizando Docker, Nginx e PostgreSQL.

## Requisitos

- [Docker](https://www.docker.com/get-started)

## Postman

### 1. Importar Coleção 

Para facilitar os testes da API, você pode importar a coleção do Postman:

- [Coleção Postman](https://api.postman.com/collections/38361214-29cb8c66-6d15-4976-a7b5-5ac320120e96?access_key=PMAT-01JSKM8XXRDJ7QG57H13P00E7R)

1. Abra o Postman.
2. Vá para "Importar".
3. Selecione o arquivo da coleção.


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

### 6. Gerar chave JWT

```bash
docker-compose exec app php artisan jwt:secret
```

### 7. Executar seeders para dados iniciais

```bash
docker-compose exec app php artisan db:seed
```

## Acessando a Aplicação

Após completar a configuração, acesse a aplicação em:

```
http://localhost:8000
```

Acesse o Mailpit em:
```
http://localhost:8025
```


## Rodando schedule
Para rodar o schedule, execute o seguinte comando:

```bash
docker-compose exec app php artisan schedule:work
```

## Rodando testes
Para rodar os testes, execute o seguinte comando:

```bash
docker-compose exec app php artisan test
```
ou
```bash
docker-compose exec app ./vendor/bin/pest
```
