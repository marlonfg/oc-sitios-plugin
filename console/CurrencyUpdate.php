<?php


namespace MarlonFreire\Sitios\Console;


use biller\bcu\Cotizaciones;
use Illuminate\Console\Command;
use Lovata\Shopaholic\Models\Currency;

class CurrencyUpdate extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'currency:update';

    /**
     * @var string The console command description.
     */
    protected $description = 'Actualizar el valor de la moneda';

    public function handle(){
        $value = Cotizaciones::obtenerCotizacion();

        $uyu = Currency::whereCode('UYU')->orWhere('symbol','$')->first();

        if($uyu && $value){
            $uyu->rate = 1/$value;
            $uyu->save();

            $this->output->writeln('Moneda actualizada!!!');
        }else
            $this->output->writeln('No se ha podido actualizar la moneda!!!');
    }
}