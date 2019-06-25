<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return 'Servidor Online!';
});

$router->post('auth/login', 'AuthController@loginPortal');
$router->post('admin/auth/login', 'AuthController@loginAdmin');
$router->post('auth/refresh', 'AuthController@refresh');
$router->post('auth/forgot', 'AuthController@forgot');
$router->post('auth/checkToken', 'AuthController@checkToken');
$router->post('auth/reset', 'AuthController@reset');

$router->get('users/me', 'User\\MeController@me');
$router->put('users/me', 'User\\MeController@update');
$router->put('users/me/change', 'User\\MeController@change');

$router->get('users', 'User\\UserController@index');
$router->post('users', 'User\\UserController@store');
$router->get('users/{id}', 'User\\UserController@show');
$router->put('users/{id}', 'User\\UserController@update');
$router->delete('users/{id}', 'User\\UserController@destroy');


