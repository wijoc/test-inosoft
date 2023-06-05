<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Project

This is repository for REST API to fulfill PT. Inosoft Trans Sistem Back-end test. Stack used:

- [Laravel 8](https://laravel.com).
- [PHP 8](https://php.com).
- [MongoDB](https://mongodb.com).

## Before you install

This project build on php 8.0, so make sure you run it on php version >= 8.0 (i would recommend to use 8.1).
For mongoDB i would recommend running on newer version like version 6, and to be honest i don't know if this will run in version 4.x.
This is my firsttime using laravel with mongodb so it's make this project is full of vulnerabilities, and queries that are not optimal. Feedback will be of great help.

Make sure this program is already installed in your environment:
- [PHP v8](https://php.com).
- [MongoDB](https://mongodb.com).
- [Composer](https://getcomposer.org/).
- Any text editor.

## How to install

1. Download or clone this reposiroty using command:
```bash
git clone https://github.com/wijoc/test-inosoft.git

2. Install laravel project tih this command:
```bash
composer install
```

3. Create .env file
```bash
cp .env.example .env
```

4. Configure .env file
```bash
DB_CONNECTION=mongodb
DB_HOST=127.0.0.1
DB_PORT=27017
DB_DATABASE=[database-name]
DB_USERNAME=[database-username]
DB_PASSWORD=[database-password]
```

5. Generate app key
```php
php artisan key:generate
```

6. Generate jwt key
```php
php artisan jwt:secret
```

7. Migrate jsonschema
```php
php artisan migrate:fresh
```

8. Run this command to run laravel
```php
php artisan serve
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).