<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration
{
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('status', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('department_id')->nullable();
            $table->string('campus', 50)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teams');
    }
}
