<?php namespace MarlonFreire\Sitios\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateTableProductsAddFieldStock extends Migration
{
    const TABLE_NAME = 'lovata_shopaholic_products';

    /**
     * Apply migration
     */
    public function up()
    {
        if (!Schema::hasTable(self::TABLE_NAME) || Schema::hasColumn(self::TABLE_NAME, 'stock')) {
            return;
        }

        Schema::table(self::TABLE_NAME, function (Blueprint $obTable) {
            $obTable->integer('stock')->nullable();
        });
    }

    /**
     * Rollback migration
     */
    public function down()
    {
        if (!Schema::hasTable(self::TABLE_NAME) || !Schema::hasColumn(self::TABLE_NAME, 'stock')) {
            return;
        }

        Schema::table(self::TABLE_NAME, function (Blueprint $obTable) {
            $obTable->dropColumn(['stock']);
        });
    }
}