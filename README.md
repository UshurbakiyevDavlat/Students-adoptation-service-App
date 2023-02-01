# Unik service. Diploma of IITU project.
# Based on laravel.

## Technologies:
    - Laravel version 8.83.23
    - Simple, Docker-based application
    - Mysql 5.7 database
    - Nginx latest
    - Elasticsearch
    - RestAPI. 
    - phpUnit Tests.
    - Websockets.
    - Telescope
    - Filters. 
    - Pagination. 
    - Swagger. 
    - Docker-compose.
    - Localization

## Integrations:
    - Twillio
    - Mobizon

## Installation
    - docker-compose up -d
    - docker-compose exec app composer install (Установка зависимостей)
    - docker-compose exec app php artisan migrate (Миграции БД)
    - docker-compose exec app php artisan key:generate (Генерация ключа приложения)
    - docker-compose exec app php artisan storage:link (Создание ссылки на папку хранения файлов)
    - sudo chmod 777 -R ./   (В случае если папка storage будет ругаться на права)
    - docker-compose exec app php artisan optimize:clear (Очистка кэша всего)
    
