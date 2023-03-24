<?php namespace MarlonFreire\Sitios\Updates;

use October\Rain\Database\Updates\Seeder;
use RainLab\Translate\Models\Locale;

class SeedLocale extends Seeder
{

    public function run()
    {
        Locale::create([
            'code' => 'es',
            'name' => 'Español',
            'is_default' => true,
            'is_enabled' => true
        ]);
    }

}
