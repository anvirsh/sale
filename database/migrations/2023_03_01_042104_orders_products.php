<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('products', function (Blueprint $table) {
            $table->id(); 
            $table->string('name');        
			$table->decimal('price', 9, 2)->comment('Цена'); 
            $table->softDeletes();			
            $table->timestamps();
        });
		
         Schema::create('orders', function (Blueprint $table) {
            $table->id();
			//$table->timestamp('date');
			$table->string('date');//$table->date('date');
            $table->string('phone')->nullable();
            $table->string('email');        
            $table->string('addres');
			$table->string('geometka')->nullable();
			$table->string('mapcenter')->nullable();
			$table->smallInteger('mapzoom')->nullable();			 
			$table->decimal('total', 9, 2)->comment('Сумма заказа'); 
            $table->text('prod_ids');            
            $table->timestamps();
			//$table->foreign('product_id')->references('id')->on('products');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
    }
};
