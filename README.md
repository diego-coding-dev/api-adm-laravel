# API ADM-Laravel
[![NPM](https://img.shields.io/npm/l/laravel)](https://github.com/diego-coding-dev/adm-laravel/blob/main/LICENCE)

# Sobre o projeto

Este projeto é uma adaptação do projeto ADM-LARAVEL para uma api.

A aplicação consiste em registrar os pedidos dos clientes, onde os itens são adicionados ou retirados e posteriormente listados depois de persistidos no banco de dados.

# Endpoints

## Authentication

Route::post('check-email', 'checkEmail');

Route::post('set-password', 'setPassword');

Route::post('', 'makeAuthentication');

## Profile

Route::get('show', 'show');

Route::put('update-email', 'updateEmail');

Route::put('update-password', 'updatePassword');

## Clients

Route::get('list', 'list');

Route::post('create', 'create');

Route::get('show/{id}', 'show');

Route::put('update/{id}', 'update');

Route::delete('delete/{id}', 'delete');

## Employees

Route::get('list', 'list');

Route::post('create', 'create');

Route::put('deactive/{id}', 'deactive');

Route::put('active/{id}', 'active');

## Orders

Route::get('list', 'list');

Route::post('create', 'create');

Route::get('show/{id}', 'show');

Route::put('finish/{id}', 'finish')->middleware(['hasItemInCart']);

Route::delete('cancel/{id}', 'cancel');

## Type products

Route::get('list', 'list');

Route::post('create', 'create');

Route::get('show/{id}', 'show');

Route::put('update/{id}', 'update');

Route::delete('delete/{id}', 'delete')->middleware(['hasProduct']);

## Products

Route::get('list', 'list');

Route::post('create', 'create');

Route::get('show/{id}', 'show');

Route::put('update/{id}', 'update');

Route::post('update-image/{id}', 'updateImage');

Route::delete('delete/{id}', 'delete')->middleware(['inStock']);

## Storages

Route::get('list', 'list');

Route::get('show/{id}', 'show');

Route::put('update/{id}', 'update');

## Order items

Route::get('list/{order_id}', 'list');

Route::post('add/{order_id}', 'add')->middleware(['beforeAddOrderItem']);

Route::get('show/{id}', 'show');

Route::put('add-quantity/{id}', 'addQuantity')->middleware(['hasEnoughQuantity']);

Route::put('remove-quantity/{id}', 'removeQuantity');

Route::delete('remove/{id}', 'remove');

## Diagrama de banco de dados

![Database](https://github.com/diego-coding-dev/assets/blob/main/api-adm-laravel/api-adm-laravel.png)

# Tecnologias utilizadas

## Back end

- PHP
- Laravel 10.8.0
- MariaDB 10.4.28

## Front end

- Postman
