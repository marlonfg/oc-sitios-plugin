<?php


namespace MarlonFreire\Sitios\Updates;

use Seeder;
use Lovata\Shopaholic\Models\Currency;
use Lovata\Shopaholic\Models\Settings;

class SeederCreateCurrencyUyu extends Seeder
{
    /**
     * Run seeder
     */
    public function run()
    {

        $arDefaultCurrencyData = [
            'active'     => true,
            'is_default' => false,
            'name'       => 'UYU',
            'code'       => 'UYU',
            'symbol'     => '$',
            'rate'       => 0.023,
            'sort_order' => 2,
        ];

        try {
            Currency::create($arDefaultCurrencyData);
        } catch (\Exception $obException) {
            return;
        }
    }
}