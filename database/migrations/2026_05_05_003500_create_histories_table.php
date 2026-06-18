<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('change_request_id')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->integer('user_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('histories');
    }
}
