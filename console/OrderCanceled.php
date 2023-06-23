<?php


namespace MarlonFreire\Sitios\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Lovata\OrdersShopaholic\Models\Order;
use Lovata\OrdersShopaholic\Models\Status;


class OrderCanceled extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'order:canceled';

    /**
     * @var string The console command description.
     */
    protected $description = 'Cancelar ordenes pendientes durante 72 horas';

    /**
     * Execute the console command.
     * @return void
     */

    public function handle(){
        $status_new = Status::whereCode('new')->first();
        $status_canceled = Status::whereCode('canceled')->first();

        $orders = Order::whereStatusId($status_new->id)->get();

        $today = Carbon::today();

        foreach($orders as $order){
            if($today->diffInDays($order->updated_at) >= 3){
                $order->status_id = $status_canceled->id;
                $order->save();
            }
        }

        $this->output->writeln('Todas las ordenes pendientes con mas de 72 horas han sido canceladas!!!');
    }
}