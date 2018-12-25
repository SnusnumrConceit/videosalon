<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')
                ->comment('');
            $table->string('year')
                ->comment('год выпуска');
            $table->string('description')
                ->comment('описание');
            $table->string('cover')
                ->comment('url обложки');
            $table->string('price')
                ->comment('цена');
            $table->string('activity')
                ->comment('прокат - 1, покупка - 2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
