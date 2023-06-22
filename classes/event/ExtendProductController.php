<?php namespace MarlonFreire\Sitios\Classes\Event;

use Event;
use Backend;
use Lovata\Shopaholic\Controllers\Products;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Models\Offer;

class ExtendProductController
{
    public function subscribe()
    {
        //Agregar fields
        Products::extendFormFields(function($form, $model, $context) {
            if (!$model instanceof Product) {
                return;
            }
        
              //Agregar a fields.yaml
              $form->addFields([
                'show_no_stock' => [
                    'label' => 'Mostrar Sin Stock',
                    'span' => 'auto',
                    'default' => 0,
                    'type' => 'switch'
                ]
            ]);
        
        });

        //Agregar columns
        Products::extendListColumns(function($list, $model) {
            if (!$model instanceof Product) {
                return;
            }
         
            //Agregar a columns.yaml
            $list->addColumns([
                'sort_order' => [
                    'label' => 'Orden',
                    'type' => 'text',
                    'searchable' => 'false',
                    'sortable' => 'true',
                    'width' => '150px'
                ],
                'stock' => [
                    'label' => 'Stock',
                    'type' => 'text',
                    'searchable' => 'true',
                    'sortable' => 'true'
                ]
            ]);
        
        });

        //Agregar funciones al controller Products de Shopaholic
        Products::extend(function ($controller) {

            //Implementacion del Reorder
            $controller->implement[] = 'MarlonFreire.Sitios.Controllers.ReorderProductController';

            $controller->addDynamicProperty('reorderConfig', '$/marlonfreire/sitios/controllers/reorderproductcontroller/config_reorder.yaml');
 
        });

        Event::listen('lovata.backend.extend_list_toolbar', function ($obController) {
            if (!$obController instanceof Products) {
                return;
            }

            // Add the reorder button to the toolbar
            $url    = Backend::url('lovata/shopaholic/products/reorder');
            $label  = 'Reordenar';

            return '<a class="btn btn-default oc-icon-reorder" href="' . $url . '">' . $label . '</a>';
        });

        // Escucha el evento de actualización de la oferta
        Offer::saved(function($offer) {
            // Obtener el producto relacionado
            $product = $offer->product;
            // Calcular el nuevo stock del producto
            $newStock = $product->offer->sum('quantity');
            // Actualizar el campo de stock del producto
            $product->stock = $newStock;
            // Desactivar si stock es cero
            if($newStock == 0)
                $product->active = 0;
            $product->save();
        });

        // Escucha el evento de actualización de la oferta
        Offer::deleted(function($offer) {
            // Obtener el producto relacionado
            $product = $offer->product;
            // Calcular el nuevo stock del producto
            $newStock = $product->offer->sum('quantity');
            // Actualizar el campo de stock del producto
            $product->stock = $newStock;
            // Desactivar si stock es cero
            if($newStock == 0)
                $product->active = 0;
            $product->save();
        });
    }
}