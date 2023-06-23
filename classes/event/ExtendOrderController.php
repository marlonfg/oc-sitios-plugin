<?php namespace MarlonFreire\Sitios\Classes\Event;

use Backend;
use Event;
use Lovata\OrdersShopaholic\Controllers\Orders;
use Lovata\OrdersShopaholic\Models\Order;
use Renatio\DynamicPDF\Classes\PDF;
use Renatio\DynamicPDF\Models\Template;

class ExtendOrderController
{
    public function subscribe(){
         //Agregar funciones al controller Orders de Shopaholic
        Orders::extend(function ($controller){
            $controller->implement[] = 'Backend.Behaviors.ImportExportController';

            $controller->addDynamicProperty('importExportConfig', '$/marlonfreire/sitios/controllers/ordercontroller/config_import_export.yaml');
 
            $controller->addDynamicMethod('onExportPDF', function ($id){
                $order = Order::find($id);

                $data = [
                    'order' => $order,
                    'site_name' => config('app.name')
                ];

                $template = Template::first();

                if(empty($template))
                    return;

                return PDF::loadTemplate($template->code , $data)->download('Orden-Nro-'.$order->order_number.'.pdf');
            });
         });

        Orders::extendListColumns(function($list, $model){
            if (!$model instanceof Order) {
                return;
            }
         
            //Agregar a columns.yaml
            $list->addColumns([
                'download' => [
                    'label' => 'PDF',
                    'type' => 'partial',
                    'path' => '$/marlonfreire/sitios/controllers/ordercontroller/_download_pdf.htm'
                ],
            ]);
        });

        Event::listen('lovata.backend.extend_list_toolbar', function ($obController) {
            if (!$obController instanceof Orders) {
                return;
            }

            // Add the reorder button to the toolbar
            $url    = Backend::url('lovata/ordersshopaholic/orders/export');
            $label  = 'CSV';

            return '<a class="btn btn-primary oc-icon-download" href="' . $url . '">' . $label . '</a>';
        });

    }
}