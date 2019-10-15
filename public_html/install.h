#!/bin/bash
cd server-alimentos-bebidas
composer install
php artisan migrate
cd ..
cd server-alojamiento
composer install
php artisan migrate
cd ..
cd server-auth
composer install
php artisan migrate
chmod -Rf 0777 storage/
cd ..
cd server-base
composer install
php artisan migrate
cd ..
cd server-catastro
composer install
php artisan migrate
cd ..
cd server-consultor
composer install
cd ..
cd server-dinardap
composer install
php artisan migrate
cd ..
cd server-exporter
composer install
php artisan migrate
cd ..
cd server-financiero
composer install
php artisan migrate
cd ..
cd server-mailer
composer install
cd ..