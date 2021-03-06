<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSasRequestunittrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sas_requestunittrack', function (Blueprint $table) {
            $table->increments('id');
            
$table->integer('request_fid')->unsigned()->nullable()->index();
$table->foreign('request_fid')->references('id')->on('sas_request');
$table->integer('unit_fid')->unsigned()->nullable()->index();
$table->foreign('unit_fid')->references('id')->on('sas_unit');
$table->integer('user_fid')->unsigned()->nullable()->index();
$table->foreign('user_fid')->references('id')->on('users');
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
        Schema::dropIfExists('requestunittracks');
    }
}
