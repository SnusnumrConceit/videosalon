<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')
                ->unique()
                ->comment('email');
            $table->string('first_name')
                ->comment('имя');
            $table->string('last_name')
                ->comment('фамилия');
            $table->string('password')
                ->comment('пароль');
            $table->unsignedInteger('role_id')
                ->comment('роль');
            $table->rememberToken()
                ->comment('ключ запоминания');
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
        Schema::dropIfExists('users');
    }
}
