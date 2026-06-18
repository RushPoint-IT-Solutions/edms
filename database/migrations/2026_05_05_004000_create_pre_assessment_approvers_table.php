<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreAssessmentApproversTable extends Migration
{
    public function up()
    {
        Schema::create('pre_assessment_approvers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pre_assessment_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('status')->nullable();
            $table->string('remarks')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->date('start_date')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pre_assessment_approvers');
    }
}
