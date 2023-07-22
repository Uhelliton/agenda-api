<p align="center"><a href="" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

### ⏳ Ponto de partida

Configure o arquivo `.env` na raiz do projeto, use como base o `.env.exempe`

- PHP 8.1x
- MYSQL 8

### 🛠️ Desenvolvimento
**Docker**
```bash

# instação containers docker
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
**Sessão na api com usuário genérico**
```bash
 email: admin@agenda.com.br
 password: admin
```

### 🛠 Tecnologias
As seguintes ferramentas foram usadas na construção do projeto:

- [PHP](https://www.php.net/)
- [Laravel](https://laravel.com/)
- [PHP Unit](https://phpunit.de/)
