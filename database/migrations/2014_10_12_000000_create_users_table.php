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
            $table->string('name',30)->unique();
            $table->string('email',30)->unique();
            $table->string('password');
            $table->string('country',30);
            $table->date('date');
            $table->string('phone',13);
            $table->integer('active')->default(0);
            $table->boolean('role')->default(0);
            $table->integer('level_id')->default(0);
            $table->enum('status',['timed out', 'playing', 'completed']);
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
