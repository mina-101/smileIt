<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Project

This project tries to create a sample api for transferring money between any two bank accounts. Using this web application you can:

- Create Admins
- Create Customers
- Login Users
- Create accounts for users
- Transfer money from one account to another
- Get balance change history of accounts

## Installation

- Git clone using: https://github.com/mina-101/smileIt.git
- Init application by editing .env file
- If you have docker installed on your machine simply go to project root directory and run ./vendor/bin/sail up -d
- Go to mysql container and create database
- Go to laravel container and run php artisan migrate:fresh --seed

## Usage
- You can check out endpoints documentation **[Here](https://github.com/mina-101/smileIt/tree/dev/docs)**

## Running Tests
- Set test environment
- Run php artisan test
