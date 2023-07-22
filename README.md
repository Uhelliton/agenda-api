<p align="center"><a href="https://laravel.com/" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
<img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
</p>
<p align="center">
http://localhost:8098/api
</p>
<p align="center">
<a href="http://localhost:8098/documentation" target="_blank">API Documentação</a>
</p>

### ⏳ Ponto de partida

Configure o arquivo `.env` na raiz do projeto, use como base uma cópia do `.env.exemple`

- PHP 8.1x
- MYSQL 8

### 🛠️ Desenvolvimento
**Docker**
```bash

# instação containers docker 
# * necessário .env na raiz do projeto para criação do banco de dados de forma automática
docker-compose up -d

# Instale as dependências necessárias para funcionamento do framework laravel
docker exec -it agenda-php bash
composer install # *Se necessário execute composer update

# gerando migrações do banco de dados
php artisan migrate

# gerando seeds para popular o banco de dados com dados inicias
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=EventTypeSeeder
```



### 
**Documentação Swagger**
```bash
# atualiza a documentação se houver mudanças
php artisan swagger
```


### 
**Sessão na api com usuário genérico**
```bash
 email: admin@agenda.com.br
 password: admin
```

### Test

```bash
# all tests
php artisan test

# unit tests
php artisan test --testsuite=Unit

# e2e/feature tests
php artisan test --testsuite=Feature

```

### 🛠 Tecnologias
As seguintes ferramentas foram usadas na construção do projeto:

- [PHP](https://www.php.net/)
- [Laravel](https://laravel.com/)
- [PHP Unit](https://phpunit.de/)
