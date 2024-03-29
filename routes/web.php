<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sanaol', 'SanaolController@sanaol');

Route::any('{slug}', function () {
    return view('welcome');
});


// Tag Routes
Route::get('app/created_tag', 'AdminController@getTag');
Route::post('app/created_tag', 'AdminController@addTag');
Route::post('app/edit_tag', 'AdminController@editTag');
Route::post('app/delete_tag', 'AdminController@deleteTag');
Route::post('app/upload', 'AdminController@upload');
Route::post('app/delete_image', 'AdminController@deleteImage');

// Category Routes
Route::post('app/create_category', 'AdminController@addCategory');
Route::post('app/edit_category', 'AdminController@editCategory');
Route::get('app/created_category', 'AdminController@getCategory');