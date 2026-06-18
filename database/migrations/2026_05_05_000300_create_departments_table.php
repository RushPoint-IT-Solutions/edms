<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentsTable extends Migration
{
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->string('status')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('permit_accountable')->nullable();
            $table->integer('company_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('departments');
    }
}
