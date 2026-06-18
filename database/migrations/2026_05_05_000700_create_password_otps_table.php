<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePasswordOtpsTable extends Migration
{
    public function up()
    {
        Schema::create('password_otps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->string('otp');
            $table->integer('attempts')->nullable();
            $table->dateTime('expires_at');
            $table->dateTime('last_sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('password_otps');
    }
}
