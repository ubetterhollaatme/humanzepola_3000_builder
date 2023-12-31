#!/bin/bash

chown -R www-data:www-data /var/www

composer install

cd /var/www/html/humanzepola3000_service

cat ./.env.example > ./.env

php artisan key:generate
php artisan migrate

php-fpm