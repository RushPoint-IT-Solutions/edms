<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcknowledgementsTable extends Migration
{
    public function up()
    {
        Schema::create('acknowledgements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('change_request_id')->nullable();
            $table->string('file')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->integer('user_id')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('acknowledgements');
    }
}
