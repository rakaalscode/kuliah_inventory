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

Auth::routes();

Route::group(['middleware' => ['admin']], function () {
    Route::resource('/customers', 'CustomerController')->except(['create','show']);
    Route::get('/customers/data', 'CustomerController@data')->name('customers.data');
});

Route::resource('/categories', 'CategoryController')->except(['create','show']);
Route::get('/categories/data', 'CategoryController@data')->name('categories.data');
Route::resource('/products', 'ProductController')->except(['create','show']);
Route::get('/products/data', 'ProductController@data')->name('products.data');
Route::resource('/sales', 'SalesController')->except(['create','show']);
Route::get('/sales/data', 'SalesController@data')->name('sales.data');
Route::resource('/suppliers', 'SupplierController')->except(['create','show']);
Route::get('/suppliers/data', 'SupplierController@data')->name('suppliers.data');
Route::resource('/productins', 'ProductInController')->except(['create','show']);
Route::get('/productins/data', 'ProductInController@data')->name('productins.data');
Route::resource('/productouts', 'ProductOutController')->except(['create','show']);
Route::get('/productouts/data', 'ProductOutController@data')->name('productouts.data');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
