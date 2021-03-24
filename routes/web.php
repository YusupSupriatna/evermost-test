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
    return $router->app->version();
});



$router->group(['prefix' => 'api'],  function() use ($router){
    $router->get('barang', 'BarangController@index');
    $router->post('barang/store', 'BarangController@store');
    $router->post('barang/update', 'BarangController@update');
    $router->delete('barang/delete', 'BarangController@delete');

    $router->post('transaksi/{id}/stock', 'TransaksiController@stock');
    $router->post('transaksi/checkout', 'TransaksiController@checkout');
});


