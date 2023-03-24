<?php

// Route::get('/producto/ficha/{id}', 'MarlonFreire\Sitios\Controllers\ProductoController@getProductItem');
Route::prefix('api')->group(function () {
    Route::get('/categorias', 'MarlonFreire\Sitios\Controllers\RestController@getCategories');
    Route::get('/fetchAll', 'MarlonFreire\Sitios\Controllers\RestController@getProducts');
    Route::get('/carrito/fetch', 'MarlonFreire\Sitios\Controllers\RestController@getCart');
});