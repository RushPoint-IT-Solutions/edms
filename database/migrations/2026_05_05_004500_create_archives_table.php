<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchivesTable extends Migration
{
    public function up()
    {
        Schema::create('archives', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('permit_id')->nullable();
            $table->string('title')->nullable()->default('');
            $table->string('description')->nullable()->default('');
            $table->integer('company_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('accountable_person')->nullable();
            $table->string('file')->nullable()->default('');
            $table->date('expiration_date')->nullable();
            $table->integer('user_id')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->string('type')->nullable()->default('');
        });
    }

    public function down()
    {
        Schema::dropIfExists('archives');
    }
}
