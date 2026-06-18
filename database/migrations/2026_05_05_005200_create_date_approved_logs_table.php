<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDateApprovedLogsTable extends Migration
{
    public function up()
    {
        Schema::create('date_approved_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->date('date_approved')->nullable();
            $table->timestamps();
            $table->integer('change_request_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('date_approved_logs');
    }
}
