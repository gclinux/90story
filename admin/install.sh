#!bin/sh
composer install
php artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider"
php php artisan admin:import redis-manager
php artisan vendor:publish --tag=laravel-admin-grid-lightbox
php artisan vendor:publish --tag=laravel-admin-wangEditor
php artisan admin:install
ln -s ../spider/static/books public/images/books