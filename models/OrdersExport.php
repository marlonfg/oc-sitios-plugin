<?php namespace MarlonFreire\Sitios\Models;

use Backend\Models\ExportModel;
use Lovata\OrdersShopaholic\Models\Order;
use Lovata\Shopaholic\Classes\Helper\CurrencyHelper;

class OrdersExport extends ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $orders = Order::all();
        $orders->each(function($order) use ($columns) {
            $order->addVisible($columns);

            $currency = CurrencyHelper::instance()->getActiveCurrencySymbol();

            foreach($columns as $col){
                switch($col){
                    case 'comprador':
                        $order->comprador = $order->property['name'];
                    // case 'producto':
                    //     $order->producto = isset($order->order_offer()->first()->offer)
                    //     ? $order->order_offer()->first()->offer->name : "";
                    case 'status_name':
                        $order->status_name = $order->status->name;
                    case 'currency_name':
                         $order->currency_name = $currency;
                     case 'total_price':
                         $order->total_price = $order->total_price_value;
                    default:
                        break;
                }
            }

        });

        return $orders->toArray();
    }
}