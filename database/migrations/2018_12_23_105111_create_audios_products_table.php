<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudiosProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audios_products', function (Blueprint $table) {
            $table->unsignedInteger('audio_id')
                ->comment('идентификатор озвучки');
            $table->unsignedInteger('product_id')
                ->comment('идентификатор фильма');
        });

        Schema::table('audios_products', function (Blueprint $table) {
           $table->foreign('audio_id')
                ->references('id')
                ->on('audio')
                ->onDelete('cascade');
        });

        Schema::table('audios_products', function (Blueprint $table) {
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
        Schema::table('audios_products', function (Blueprint $table) {
           $table->dropForeign(['product_id']);
            $table->dropForeign(['audio_id']);
        });
        Schema::dropIfExists('audios_products');
    }
}
