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
$router->post('users/me/avatar', 'User\\MeController@updateAvatar');
$router->put('users/me/reset', 'User\\MeController@changePassword');
$router->get('users/me/books', 'User\\MeController@books');
$router->get('users/me/likes', 'User\\MeController@getLikedBooks');
$router->post('users/me/likes/{bookId}', 'User\\MeController@likeBook');
$router->post('users/me/reports/{reportedId}', 'User\\MeController@report');

$router->get('users', 'User\\UserController@getUsers');
$router->post('users', 'User\\UserController@postUser');
$router->get('users/{id}', 'User\\UserController@getUser');
$router->put('users/{id}', 'User\\UserController@putUser');
$router->delete('users/{id}', 'User\\UserController@destroy');

$router->post('users/mobile-token', 'Notification\\ServiceWorkerController@receiveClientToken');


$router->get('categories', 'Category\\CategoryController@getCategories');
$router->post('categories', 'Category\\CategoryController@postCategory');
$router->get('categories/{id}', 'Category\\CategoryController@getCategory');
$router->put('categories/{id}', 'Category\\CategoryController@putCategory');
$router->delete('categories/{id}', 'Category\\CategoryController@deleteCategory');

$router->get('books', 'Book\\BookController@getBooks');
$router->get('books/status', 'Book\\StatusController@getStatus');
$router->get('books/conditions', 'Book\\ConditionController@getConditons');
$router->get('books/validate', 'Book\\BookController@getBooksToValidate');
$router->get('books/approved', 'Book\\BookController@getApprovedBooks');
$router->get('books/refused', 'Book\\BookController@getRefusedBooks');
$router->get('books/week', 'Book\\BookController@getWeeklyBooks');
$router->get('books/highlights', 'Book\\BookController@getHighlightsBooks');
$router->post('books', 'Book\\BookController@postBook');
$router->get('books/{id}', 'Book\\BookController@getBook');
$router->put('books/{id}', 'Book\\BookController@putBook');
$router->put('books/{id}/status', 'Book\\BookController@changeStatus');
$router->delete('books/{id}', 'Book\\BookController@deleteBook');

$router->post('books/{bookId}/image/{imageId}', 'Book\\ImageController@updateImage');
$router->delete('books/{bookId}/image/{imageId}', 'Book\\ImageController@deleteImage');

$router->get('rooms', 'Chat\\ChatController@getRooms');
$router->get('rooms/{roomId}/messages', 'Chat\\ChatController@getMessages');
$router->post('rooms/{advertiserId}/w/{buyerId}', 'Chat\\ChatController@postChatAndMessage');
$router->post('rooms/{roomId}/messages', 'Chat\\ChatController@sendMessage');

$router->get('reports', 'Report\\ReportController@getReports');
$router->get('reports/status', 'Report\\StatusController@getStatus');
$router->put('reports/{id}', 'Report\\ReportController@putReport');
$router->delete('reports/{id}', 'Report\\ReportController@deleteReport');

$router->post('sw/subscriptions', 'Notification\\ServiceWorkerController@subscribeClient');
$router->delete('sw/subscriptions/{id}', 'Notification\\ServiceWorkerController@unsubscribeClient');

$router->put('notifications/{notificationId}', 'Notification\\NotificationController@openNotification');
