# Symfony ecommerce project

## Requirements
- Docker
- Docker-compose
- symfony
- php
- composer


## Installation
- Clone the project
- Run `composer install`
- Run `docker-compose up -d` to start the database
- .env-example should be renamed to .env and filled with the correct values

## Usage
```shell
php bin/console make:migration # to start the migration
php bin/console doctrine:migrations:migrate # to create the database
symfony console doctrine:fixtures:load # to load the fixtures
symfony server:start # to start the server
```

## Repositories
- [Origin]("https://github.com/EpitechMscProPromo2025/T-WEB-600-LIL-6-1-ecommerce-antoine.baheux")
- [Private]("https://github.com/Heifarabuval/Ecommerce")

## Login request

- POST /api/login login user
```shell
curl --location 'http://127.0.0.1:8000/api/login' \
--header 'Content-Type: application/json' \
--header 'Cookie: PHPSESSID=o6u9dul1aqcum6901d0h0e6jmt' \
--data '{
    "login":"domak",
    "password":"azertyuiop"
}'
```

- POST /api/register register a new user
```shell
curl --location 'http://127.0.0.1:8000/api/register' \
--header 'Content-Type: application/json' \
--header 'Cookie: PHPSESSID=o6u9dul1aqcum6901d0h0e6jmt' \
--data-raw '{
    "firstname":"Domak",
    "lastname":"Jean",
    "email":"jean@domak.com",
    "login":"domak",
    "password":"azertyuiop"
}'
```

- GET /api/users get current user
```shell
curl --location 'http://127.0.0.1:8000/api/users' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.cfgfcvbhgfvbgvbhgvbhgbvh' \
--header 'Cookie: PHPSESSID=o6u9dul1aqcum6901d0h0e6jmt'
```

All the other routes are protected by the JWT token so you need to add the Authorization header to your request like bellow.


## Erreur solving

If you have this error do the following command

- Unable to create a signed JWT from the given configuration

```shell
php bin/console lexik:jwt:generate-keypair --overwrite
```