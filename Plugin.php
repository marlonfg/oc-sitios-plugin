<?php namespace MarlonFreire\Sitios;

use MarlonFreire\Sitios\Console\CurrencyUpdate;
use MarlonFreire\Sitios\Console\OrderCanceled;
use MarlonFreire\Sitios\Console\ZureoUpdate;
use System\Classes\PluginBase;
use MarlonFreire\Sitios\Console\Deploy;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Controllers\Products;

class Plugin extends PluginBase
{

    public $require = ['RainLab.Location', 'RainLab.Translate', 'Lovata.Shopaholic'];

    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function register()
    {
        $this->registerConsoleCommand('assets:deploy', Deploy::class);
        $this->registerConsoleCommand('zureo:update', ZureoUpdate::class);
        $this->registerConsoleCommand('currency:update', CurrencyUpdate::class);
        $this->registerConsoleCommand('order:canceled', OrderCanceled::class);
    }

    public function registerFormWidgets()
    {
        return [
            'MarlonFreire\Sitios\FormWidgets\SelectSimple' => [
                'label' => 'SelectSimple',
                'code' => 'SelectSimple'
            ]
        ];
    }

    public function boot(){
        
        // Agregar campos y funciones al modelo Product
        Product::extend(function ($model) {
            $model->addFillable([
                'stock',
                'show_no_stock',
                'sort_order',
                'proveedor_id',
            ]);

            $model->addCached([
                'stock',
                'show_no_stock',
                'sort_order',
                'proveedor_id',
            ]);

            //Agregar a fields.yaml
            $model->addDynamicField('show_no_stock', [
                'label' => 'Mostrar Sin Stock',
                'span' => 'right',
                'default' => 0,
                'type' => 'switch',
            ]);

            //Agregar a columns.yaml
            $model->addDynamicColumn('sort_order', [
                'label' => 'Orden',
                'type' => 'text',
                'searchable' => 'false',
                'sortable' => 'true',
                'width' => '150px'
            ]);

            $model->addDynamicColumn('stock', [
                'label' => 'Stock',
                'type' => 'text',
                'searchable' => 'true',
                'sortable' => 'true'
            ]);
        });

        //Agregar cambios y funciones a las ofertas
        Offer::extend(function ($model) {
            $model->addFillable([
                'sort_order',
                'currency_id',
                'precio_por_mayor',
                'sale_price',
                'discount_groups_id'
            ]);

            $model->addCached([
                'sort_order',
                'currency_id',
                'precio_por_mayor',
                'sale_price',
                'discount_groups_id',
                'currency_symbol',
            ]);

            $model->addJasonable([
                'precio_por_mayor'
            ]);

            $model->addAppends([
                'currency_symbol'
            ]);

            $model->belongsTo['currency'] = [Currency::class];
            $model->belongsTo['discount_groups'] = [DiscountGroups::class, 'key' => 'discount_groups_id'];

            //Agregar a columns.yaml
            $model->addDynamicField('sort_order', [
                'label' => 'Orden',
                'type' => 'text',
                'span' => 'left',
                'default' => 0,
                'tab' => 'lovata.toolbox::lang.tab.settings'
            ]);

            $model->addDynamicField('currency', [
                'label' => 'Moneda',
                'type' => 'relation',
                'span' => 'left',
                'permissions' => -'shopaholic-offer-multi-moneda',
                'tab' => 'lovata.toolbox::lang.tab.settings'
            ]);

            $model->addDynamicField('precio_por_mayor', [
                'label' => 'Intervalos de precios',
                'prompt' => 'Adicionar Nuevo Intervalo',
                'type' => 'repeater',
                'span' => 'auto',
                'permissions' => -'shopaholic-offer-precio-por-mayor',
                'tab' => 'Precio Por Mayor',
                'forms' => [
                    'fields'  => [
                        'cantidad' => [
                            'label' => 'Cantidad',
                            'span' => 'auto',
                            'type' => 'number'
                        ],
                        'precio' => [
                            'label' => 'Precio',
                            'span' => 'auto',
                            'type' => 'number'
                        ],
                        'texto' => [
                            'label' => 'Texto',
                            'span' => 'auto',
                            'type' => 'text',
                            'size' => 'small'
                        ],
                    ]                  
                ]
            ]);

            $model->addDynamicField('discount_groups', [
                'label' => 'Grupo de Descuentos',
                'type' => 'relation',
                'span' => 'auto',
                'permissions' => -'shopaholic-discount-manage-groups',
                'tab' => 'Precio Por Mayor'
            ]);

            //Agregar a columns.yaml
            $model->addDynamicColumn('sort_order', [
                'label' => 'Orden',
                'type' => 'text',
                'searchable' => 'false',
                'sortable' => 'true',
                'width' => '150px'
            ]);

            $model->addDynamicColumn('currency_name', [
                'label' => 'Moneda',
                'type' => 'text',
                'relation' => 'currency',
                'select' => 'name',
                'sortable' => 'true',
                'permissions' => -'shopaholic-offer-multi-moneda',
            ]);

            $model->addDynamicColumn('sale_price', [
                'label' => 'Precio de Compra',
                'type' => 'text',
                'searchable' => 'false',
                'sortable' => 'false',
                'permissions' => -'shopaholic-manage_sale_price'
            ]);

            $model->addDynamicMethod('getCurrencySymbolAttribute', function() {
                if($this->currency)
                    return $this->currency->symbol;
                else
                    return CurrencyHelper::instance()->getActiveCurrencySymbol();
            });

            $model->addDynamicMethod('getSalePriceAttribute', function() {
                return !empty($this->attributes['sale_price']) ? $this->attributes['sale_price'] : 0;
            });

            
        });

        //Agregar funciones al controller Products de Shopaholic
        Products::extend(function ($controller) {
            $controller->addDynamicMethod('onReplicate', function () {
                // Código para la función onReplicate
                if(Input::has('Product')){
                    $prod_shop = Product::whereSlug(Input::get('Product.slug'))->first();

                    $new = Product::create([
                        'name' => Input::get('Product.name'),
                        'active'=> $prod_shop->active,
                        'featured'=> $prod_shop->featured,
                        'show_no_stock'=> $prod_shop->show_no_stock,
                        'preview_text'=> $prod_shop->preview_text,
                        'category_id'=> $prod_shop->category->id,
                        'brand_id'=> isset($prod_shop->brand) ? $prod_shop->brand->id : null,
                        'popularity'=> $prod_shop->popularity,
                    ]);

                    if(Input::has('Product.listing_type_id')){
                        $new->listing_type_id = $prod_shop->listing_type_id;
                        $new->meli_condition = $prod_shop->meli_condition;
                        $new->free_shipping = $prod_shop->free_shipping;
                        $new->description = $prod_shop->description;

                        $new->save();
                    }

                    if(Input::has('Product.additional_category'))
                        $new->additional_category()->sync($prod_shop->additional_category->pluck('id')->toArray());


                    if($prod_shop->offer)
                        foreach($prod_shop->offer as $offer){
                            $new_offer = $offer->replicate();
                            $new_offer->Push();

                            $new_offer->price = $offer->price_value;
                            $new_offer->old_price = $offer->old_price_value;
                            $new_offer->product_id = $new->id;
                            $new_offer->save();
                        }

                    return Redirect::to('/backend/lovata/shopaholic/products/update/'.$new->id);
                }
            });

            $controller->addDynamicMethod('onMakeOffers', function () {
                // Código para la función onMakeOffers
                if(Input::has('Product')) {
                    $prod_shop = Product::whereSlug(Input::get('Product.slug'))->first();

                    if(Input::has('Product.property')){
                        $property = Input::get('Product.property');
                    }else
                        return;

                    $offer_first = $prod_shop->offer->first();

                    if(!$offer_first)
                        return;

                    //algoritmo de combinaciones

                    $ar = $property;

                    $counts = array_map("count", $ar);
                    $total = array_product($counts);
                    $res = [];

                    $combinations = [];
                    $curCombs = $total;

                    foreach ($ar as $field => $vals) {
                        $curCombs = $curCombs / $counts[$field];
                        $combinations[$field] = $curCombs;
                    }

                    for ($i = 0; $i < $total; $i++) {
                        foreach ($ar as $field => $vals) {
                            $res[$i][$field] = $vals[($i / $combinations[$field]) % $counts[$field]];
                        }
                    }

                    foreach($res as $r) {
                        $offer = Offer::create([
                            'active' => 1,
                            'name' => $offer_first->name,
                            'currency_id' => $offer_first->currency_id,
                            'price' => $offer_first->price,
                            'old_price' => $offer_first->price,
                            'price_meli' => $offer_first->price,
                            'quantity' => $offer_first->quantity,
                            'product_id' => $prod_shop->id
                        ]);


                        foreach ($r as $key => $value) {
                            $prop = Property::whereId($key)->get()->first();

                            $val = $prop->property_value()->whereValue($value)->get()->first();

                            $prop_offer = PropertyValueLink::firstOrCreate([
                                'value_id' => $val->id,
                                'property_id' => $prop->id,
                                'element_id' => $offer->id,
                                'element_type' => 'Lovata\Shopaholic\Models\Offer',
                                'product_id' => $prod_shop->id
                            ]);
                        }
                    }

                    $offer_first->delete();

                    return back();
                }
            });

            $controller->addDynamicMethod('onReorder', function () {
                $obResult = parent::onReorder();
                Event::fire('shopaholic.product.update.sorting');

                return $obResult;
            });
        });
    }
}
