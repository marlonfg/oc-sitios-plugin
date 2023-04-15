<?php namespace MarlonFreire\Sitios\Classes\Event;

use Lovata\Shopaholic\Models\Product;
use October\Rain\Database\Scopes\SortableScope;

class ExtendProductModel
{
    public function subscribe()
    {
        // Agregar campos y funciones al modelo Product
        Product::extend(function ($model) {

            $model->addFillable([
                'stock',
                'show_no_stock',
                'sort_order'
            ]);

            $model->addCachedField([
                'stock',
                'show_no_stock',
                'sort_order'
            ]);

            $model::addGlobalScope(new SortableScope);

            // Add the sortable behavior to the product model
            $model->bindEvent('model.afterCreate', function () use ($model){
                $sortOrderColumn = $model->getSortOrderColumn();
    
                if (is_null($model->$sortOrderColumn)) {
                    $model->setSortableOrder($model->getKey());
                }
            });

            $model->addDynamicMethod('getSortOrderColumn', function () {
                return 'sort_order';
            });

            $model->addDynamicMethod('getQualifiedSortOrderColumn', function () use($model){
                return $model->getTable().'.'.$model->getSortOrderColumn();
            });

            $model->addDynamicMethod('setSortableOrder', function ($itemIds, $itemOrders = null) use ($model){
                if (!is_array($itemIds)) {
                    $itemIds = [$itemIds];
                }
        
                if ($itemOrders === null) {
                    $itemOrders = $itemIds;
                }
        
                foreach ($itemIds as $index => $id) {
                    $order = $itemOrders[$index];
                    $model->newQuery()->where($model->getKeyName(), $id)->update([$model->getSortOrderColumn() => $order]);
                }
            });


        });
    }
}