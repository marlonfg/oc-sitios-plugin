<?php namespace MarlonFreire\Sitios\Updates;

use October\Rain\Database\Updates\Seeder;
use RainLab\Location\Models\Country;
use Schema;

class SeedUyStates extends Seeder
{
    public function run()
    {
        if (Schema::hasTable('rainlab_location_countries') && Schema::hasTable('rainlab_location_states')) {

            $uy = Country::whereCode('UY')->first();

            if ($uy->states()->count() > 0) {
                return;
            }

            $uy->states()->createMany([
                ['code' => 'AR', 'name' => 'Artigas'],
                ['code' => 'CA', 'name' => 'Canelones'],
                ['code' => 'CL', 'name' => 'Cerro Largo'],
                ['code' => 'CO', 'name' => 'Colonia'],
                ['code' => 'DU', 'name' => 'Durazno'],
                ['code' => 'FS', 'name' => 'Flores'],
                ['code' => 'FA', 'name' => 'Florida'],
                ['code' => 'LA', 'name' => 'Lavalleja'],
                ['code' => 'MA', 'name' => 'Maldonado'],
                ['code' => 'MO', 'name' => 'Montevideo'],
                ['code' => 'PA', 'name' => 'Paysandú'],
                ['code' => 'RN', 'name' => 'Río Negro'],
                ['code' => 'RI', 'name' => 'Rivera'],
                ['code' => 'RO', 'name' => 'Rocha'],
                ['code' => 'SA', 'name' => 'Salto'],
                ['code' => 'SJ', 'name' => 'San José'],
                ['code' => 'SO', 'name' => 'Soriano'],
                ['code' => 'TA', 'name' => 'Tacuarembó'],
                ['code' => 'TT', 'name' => 'Treinta y tres'],
            ]);
        }

    }
}
