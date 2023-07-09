<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix('v1')->group(function () {
    Route::get('books', 'App\Http\Controllers\BookController@index');
    Route::get('book/{id}','App\Http\Controllers\BookController@view')->where('id', '[0-9]+');
    Route::get('books/slug/{slug}', 'App\Http\Controllers\BookController@slug' );
    Route::get('categories/random/{count}', 'App\Http\Controllers\CategoryController@random');
    Route::get('books/top/{count}', 'App\Http\Controllers\BookController@top');
    Route::get('categories','App\Http\Controllers\CategoryController@index' );
    Route::get('categories/slug/{slug}', 'App\Http\Controllers\CategoryController@slug');
    Route::get('books/search/{keyword}', 'App\Http\Controllers\BookController@search');
});
