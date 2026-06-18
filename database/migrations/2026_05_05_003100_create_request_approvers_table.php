<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestApproversTable extends Migration
{
    public function up()
    {
        Schema::create('request_approvers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('change_request_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('level')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->integer('additional')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->longText('remarks')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('date_approved')->nullable();
            $table->string('request_type', 20)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('request_approvers');
    }
}
