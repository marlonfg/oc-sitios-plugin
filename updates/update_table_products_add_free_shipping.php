<?php


namespace MarlonFreire\Sitios\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;


class UpdateTableProductsAddFreeShipping extends Migration
{
    const TABLE_NAME = 'lovata_shopaholic_products';

    /**
     * Apply migration
     */
    public function up()
    {
        if (!Schema::hasTable(self::TABLE_NAME) || Schema::hasColumn(self::TABLE_NAME, 'free_shipping')) {
            return;
        }

        Schema::table(self::TABLE_NAME, function (Blueprint $obTable) {
            $obTable->boolean('free_shipping')->default(0);
        });
    }

    /**
     * Rollback migration
     */
    public function down()
    {
        if (!Schema::hasTable(self::TABLE_NAME) || !Schema::hasColumn(self::TABLE_NAME, 'free_shipping')) {
            return;
        }

        Schema::table(self::TABLE_NAME, function (Blueprint $obTable) {
            $obTable->dropColumn(['free_shipping']);
        });
    }
}