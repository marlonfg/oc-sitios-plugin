<?php

Route::get('/backend/lovata/ordersshopaholic/orders/pdf/{id}', 'Lovata\OrdersShopaholic\Controllers\Orders@onExportPDF');

Route::get('/get-products-by-category/{categoryId}', function($categoryId) {
    $products = \Lovata\Shopaholic\Models\Product::whereHas('category', function($query) use ($categoryId) {
        $query->where('category_id', $categoryId);
    })->get()->pluck('id');
     return $products;
});