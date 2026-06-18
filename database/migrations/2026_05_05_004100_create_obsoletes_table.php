<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObsoletesTable extends Migration
{
    public function up()
    {
        Schema::create('obsoletes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_id')->nullable();
            $table->string('control_code')->nullable()->default('');
            $table->integer('company_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->string('title')->nullable()->default('');
            $table->string('category')->nullable()->default('');
            $table->string('other_category')->nullable()->default('');
            $table->date('effective_date')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('version')->nullable();
            $table->date('created_at')->nullable();
            $table->date('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('obsoletes');
    }
}
