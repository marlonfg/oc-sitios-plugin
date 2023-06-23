<?php namespace MarlonFreire\Sitios;

use MarlonFreire\Sitios\Console\CurrencyUpdate;
use MarlonFreire\Sitios\Console\OrderCanceled;
use System\Classes\PluginBase;
use MarlonFreire\Sitios\Console\Deploy;

use Event;
use MarlonFreire\Sitios\Classes\Event\ExtendOrderController;
use MarlonFreire\Sitios\Classes\Event\ExtendProductController;
use MarlonFreire\Sitios\Classes\Event\ExtendProductModel;


class Plugin extends PluginBase
{

    public $require = ['RainLab.Location', 'Lovata.Shopaholic', 'Renatio.DynamicPDF'];

    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function register()
    {
        $this->registerConsoleCommand('sitios:deploy', Deploy::class);
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
         Event::subscribe(ExtendProductModel::class);
         Event::subscribe(ExtendProductController::class);
         Event::subscribe(ExtendOrderController::class);
    }
}
