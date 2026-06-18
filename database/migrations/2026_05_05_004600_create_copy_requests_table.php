<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCopyRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('copy_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type_of_document')->nullable();
            $table->date('date_needed')->nullable();
            $table->integer('document_id')->nullable();
            $table->string('control_code')->nullable();
            $table->integer('revision')->nullable();
            $table->integer('user_id')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->string('status')->nullable();
            $table->integer('level')->nullable();
            $table->date('expiration_date')->nullable();
            $table->integer('copy_count')->nullable();
            $table->string('title')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->text('purpose');
        });
    }

    public function down()
    {
        Schema::dropIfExists('copy_requests');
    }
}
