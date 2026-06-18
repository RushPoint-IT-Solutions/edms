<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestLogsTable extends Migration
{
    public function up()
    {
        Schema::create('request_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('change_request_id')->nullable();
            $table->string('action')->nullable();
            $table->integer('user_id')->nullable();
            $table->date('created_at')->nullable();
            $table->date('updated_at')->nullable();
            $table->text('remarks')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('request_logs');
    }
}
