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
            $table->integer('common_app_fid')->unsigned()->nullable()->index();
            $table->foreign('common_app_fid')->references('id')->on('common_app');
            $table->string('phone')->default('');
            $table->string('appuseridentifier')->default('');
            $table->integer('code')->unsigned()->default(0);
            $table->boolean('isactive')->default(0);
            $table->string('codeexpire_time')->default('-1');
            $table->string('email')->unique();
            $table->string('password');
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
