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
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->boolean('confirmed')->default(false);
            $table->string('token')->nullable();

            $table->string('phone')->nullable();
            $table->string('country_code')->nullable();
            $table->integer('zip')->nullable();
            $table->boolean('verified')->default(false);

            $table->string('country')->nullable();
            $table->string('city')->nullable();

            $table->string('age')->nullable();
            $table->string('category')->nullable();
            $table->string('education')->nullable();

            $table->string('password')->nullable();

            $table->string('authy_id')->nullable();
            $table->rememberToken();
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
