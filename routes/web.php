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
$router->put('users/me', 'User\\MeController@putMe');
$router->put('users/me/change', 'User\\MeController@change');
$router->get('users/me/likes', 'User\\MeController@getLikedBooks');
$router->post('users/me/likes/{book_id}', 'User\\MeController@likeBook');
$router->post('users/me/reports/{reportedId}', 'User\\MeController@report');

$router->get('users', 'User\\UserController@getUsers');
$router->post('users', 'User\\UserController@postUser');
$router->get('users/{id}', 'User\\UserController@getUser');
$router->put('users/{id}', 'User\\UserController@putUser');
$router->delete('users/{id}', 'User\\UserController@destroy');

$router->get('categories', 'Category\\CategoryController@getCategories');
$router->post('categories', 'Category\\CategoryController@postCategory');
$router->get('categories/{id}', 'Category\\CategoryController@getCategory');
$router->put('categories/{id}', 'Category\\CategoryController@putCategory');
$router->delete('categories/{id}', 'Category\\CategoryController@deleteCategory');

$router->get('books', 'Book\\BookController@getBooks');
$router->post('books', 'Book\\BookController@postBook');
$router->get('books/{id}', 'Book\\BookController@getBook');
$router->put('books/{id}', 'Book\\BookController@putBook');
$router->delete('books/{id}', 'Book\\BookController@deleteBook');

$router->get('rooms', 'Chat\\ChatController@getRooms');
$router->get('rooms/{room_id}/messages', 'Chat\\ChatController@getMessages');
$router->post('rooms/{advertiser_id}/w/{buyer_id}', 'Chat\\ChatController@storeMessage');
