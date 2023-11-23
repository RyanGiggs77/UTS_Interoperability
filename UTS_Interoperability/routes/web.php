<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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
    return $router->app->version();
});

$router->group(['middleware' => ['auth']], function ($router){
    $router->get('user', 'UserController@index');
    $router->post('user', 'UserController@store');
    $router->get('user/{id}', 'UserController@show');
    $router->put('user/{id}', 'UserController@update');
    $router->delete('user/{id}', 'UserController@destroy');
});


$router->group(['middleware' => ['auth']], function ($router) {
    $router->get('library', 'LibraryController@index');
    $router->post('library', 'LibraryController@store');
    $router->get('library/{id}', 'LibraryController@show');
    $router->get('/library/{id}/user', 'LibraryController@getUserPosts');
    $router->put('library/{id}', 'LibraryController@update');
    $router->delete('library/{id}', 'LibraryController@destroy');
});

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
});
