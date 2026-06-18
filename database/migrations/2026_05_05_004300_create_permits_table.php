<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermitsTable extends Migration
{
    public function up()
    {
        Schema::create('permits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('accountable_person')->nullable();
            $table->string('file')->nullable();
            $table->date('expiration_date')->nullable();
            $table->integer('user_id')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('permits');
    }
}
