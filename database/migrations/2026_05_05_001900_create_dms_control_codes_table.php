<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmsControlCodesTable extends Migration
{
    public function up()
    {
        Schema::create('control_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('description')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->integer('document_type_id')->nullable();
            $table->integer('department_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('control_codes');
    }
}
