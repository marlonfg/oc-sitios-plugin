<?php namespace MarlonFreire\Sitios\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLovataShopaholicProveedores extends Migration
{
    public function up()
    {
        Schema::create('lovata_shopaholic_proveedores', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('lovata_shopaholic_proveedores');
    }
}
