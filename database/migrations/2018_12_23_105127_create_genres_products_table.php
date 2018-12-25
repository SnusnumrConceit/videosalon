<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenresProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genres_products', function (Blueprint $table) {
            $table->unsignedInteger('genre_id')
                ->comment('идентификатор жанра');
            $table->unsignedInteger('product_id')
                ->comment('идентификатор фильма');
        });
        Schema::table('genres_products', function (Blueprint $table) {
            $table->foreign('genre_id')
                ->references('id')
                ->on('genres')
                ->onDelete('cascade');
        });

        Schema::table('genres_products', function (Blueprint $table) {
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('genres_products', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['genre_id']);
        });
        Schema::dropIfExists('genres_products');
    }
}
