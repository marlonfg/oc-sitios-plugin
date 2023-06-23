<?php namespace MarlonFreire\Sitios\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateTableProductsAddFieldShowNoStock extends Migration
{
    const TABLE_NAME = 'lovata_shopaholic_products';

    /**
     * Apply migration
     */
    public function up()
    {
        if (!Schema::hasTable(self::TABLE_NAME) || Schema::hasColumn(self::TABLE_NAME, 'show_no_stock')) {
            return;
        }

        Schema::table(self::TABLE_NAME, function (Blueprint $obTable) {
            $obTable->boolean('show_no_stock')->default(0);
        });
    }

    /**
     * Rollback migration
     */
    public function down()
    {
        if (!Schema::hasTable(self::TABLE_NAME) || !Schema::hasColumn(self::TABLE_NAME, 'show_no_stock')) {
            return;
        }

        Schema::table(self::TABLE_NAME, function (Blueprint $obTable) {
            $obTable->dropColumn(['show_no_stock']);
        });
    }
}