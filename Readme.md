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

## Erreur solving

If you have this error do the following command

- Unable to create a signed JWT from the given configuration

```shell
php bin/console lexik:jwt:generate-keypair --overwrite
```
