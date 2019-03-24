<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login','UserController@login');
Route::post('register','UserController@register');

Route::group(['middleware'=>'auth:api'], function(){
    Route::post('get-details','UserController@getDetails');

    Route::post('getAllUsers','UserController@getAllUsers');

    Route::get('singleUser/edit/{id}','UserController@singleUser');

    Route::put('updateUser/{id}','UserController@updateUser');

    Route::delete('deleteUser/{id}','UserController@deleteUser');

    Route::get('/home', 'HomeController@index')->name('home');
    Route::match(['get','post'],'/home/edit/{id}','HomeController@edit');
    Route::match(['get','post'],'/home/add','HomeController@add');
    Route::match(['get','post'],'/home/delete/{id}','HomeController@delete');

    //product
    Route::resource('product','ProductController');

    Route::get('searchProduct/{data}','ProductController@searchProduct');

    Route::get('productNotify','ProductController@productNotify');

    //cart
    Route::resource('cart','CartController');
});

// Route::get('allProduct','ProductController@AllProduct');
//all product
Route::resource('productList','ProductListController');

Route::resource('roles','RoleController');



