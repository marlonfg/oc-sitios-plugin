<?php

namespace MarlonFreire\Sitios\Controllers;

use Backend\Classes\Controller;
use Lovata\Shopaholic\Classes\Collection\CategoryCollection;
use Lovata\Shopaholic\Classes\Collection\ProductCollection;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Category;
use Lovata\OrdersShopaholic\Classes\Processor\CartProcessor;


class RestController extends Controller
{

    public function getProducts($id = null)
    {
        $result = [];
        $products = Product::with(['offer', 'category', 'brand', 'preview_image', 'images'])->where('active', 1)->get();
        foreach($products as $product)
        {
            array_push($result, $product);
        }

        return $this->send($result);
    }

    public function getCart($obShippingTypeItem = null, $obPaymentMethodItem = null)
    {
        $arrItemList = [];
        CartProcessor::instance()->setActiveShippingType($obShippingTypeItem);
        CartProcessor::instance()->setActivePaymentMethod($obPaymentMethodItem);
        $itemList = CartProcessor::instance()->get();

        foreach($itemList as $item){
            $offer = Offer::all()
                ->where('id', $item->id)->first();
            array_push($arrItemList, $offer);
        }

        return $this->send($arrItemList);
    }

    public function getCategories()
    {

        $categories = Category::all()->where('parent_id', null);

        $arr_categories = [];

        foreach($categories as $category)
        {
            if($category->categoria != null)
            {
                //Tiene una estructura similar como la de MercadoLibre
                $first_child_level = $category->children;
                foreach ($first_child_level as $sub_category) {
                    $leafs = [];
                    $this->getLeaf($sub_category->id, $leafs);
                    if ($leafs) {
                        array_push($arr_categories,
                            [
                                'id' => $sub_category->id,
                                'name' => $sub_category->name,
                                'preview_image' => $sub_category->preview_image,
                                'hijos' => $leafs
                            ]
                        );
                    }
                }
            }else {
                $leafs = [];
                if (count($category->children) > 0 )
                    $this->getLeaf($category->id, $leafs);

                array_push($arr_categories, [
                    'id' => $category->id,
                    'name' => $category->name,
                    'preview_image' => $category->preview_image,
                    'hijos' => $leafs
                ]);
            }
        }

        return $this->send($arr_categories);
    }

    private function getLeaf($id, &$leafs)
    {
        $categories = CategoryCollection::make([$id])->active();
        $products_related = ProductCollection::make()->active()->category($id);
        if (count($categories->first()->children) == 0 && count($products_related) > 0)
        {
            array_push($leafs, ['id' => $categories->first()->id, 'name' => $categories->first()->name, 'slug' => $categories->first()->slug]);
            return $leafs;
        }
        else {
            foreach ($categories->first()->children as $category){
                $this->getLeaf($category->id, $leafs);
            }
        }
    }

    //JSON Responses
    private function send($data)
    {
        return response()->json([
            'status' => 'OK',
            'data' => $data
        ]);
    }

    private function error($message = "Elemento no encontrado", $code = 404)
    {
        return response()->json([
            'status' => 'ERROR',
            'message' => $message
        ], $code);
    }

}
