# Разворачивание проекта

### Docker

1. Скопировать `.env.example` в `.env`
2. Запустить команду `docker-compose up -d`
3. Запустить команду `docker exec -it tires_php-fpm bash` и внутри контейнера запустить следующие команды:
   1. `composer install`
   2. `php artisan migrate`
   3. `php artisan permission:create-role admin customers`
   4. `npm i`
   5. `npm run build`

Для того, чтобы работала отправка письма необходимо выполнить команду:

```
php artisan queue:listen
```
