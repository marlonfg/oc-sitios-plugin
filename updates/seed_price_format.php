<?php


namespace MarlonFreire\Sitios\Updates;


use October\Rain\Database\Updates\Seeder;
use Illuminate\Support\Facades\DB;
use Schema;

class SeedPriceFormat extends Seeder
{
    public function run()
    {
        if (Schema::hasTable('system_settings')) {

            DB::table('system_settings')
                ->where('item', 'lovata_toolbox_settings')
                ->update(['value->decimals' => "", 'value->dec_point' => "comma", 'value->thousands_sep' => "dot"]);

        }

    }

}