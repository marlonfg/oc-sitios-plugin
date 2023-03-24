<?php

namespace MarlonFreire\Sitios\Controllers;

use Backend\Classes\Controller;
use Illuminate\Http\Response;
use Lovata\Shopaholic\Models\Product;

class ProductoController extends Controller
{
    public function getProductItem($id) {
        $result = [];
        $products = Product::with(['offer', 'category', 'brand', 'preview_image', 'images'])
            ->where('active', 1)
            ->where('id', $id)
            ->get();

            foreach($products as $product)
            {
                array_push($result, [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    // 'preview_text' => $product->preview_text,
                    'brand' => $product->brand,
                    'category' => $product->category,
                    'image' => $product->preview_image,
                    'gallery' => $product->images,
                    'offer' => $product->offer,
                    'rating' => $product->rating
                ]);
            }
            return $this->makeView('ficha', ['model' => $result]);
    }
}