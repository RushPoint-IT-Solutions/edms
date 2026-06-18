<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('control_code')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('office_id')->nullable();
            $table->string('title')->nullable();
            $table->string('category')->nullable();
            $table->string('other_category')->nullable();
            $table->date('effective_date')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('version')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->integer('public')->nullable();
            $table->string('old_control_code')->nullable();
            $table->integer('last_number')->nullable();
            $table->string('status')->nullable();
            $table->string('soft_copy')->nullable();
            $table->string('pdf_copy')->nullable();
            $table->string('fillable_copy')->nullable();
            $table->integer('process_owner')->nullable();
            $table->integer('folder_id')->nullable();
            $table->date('date_approved')->nullable();
            $table->string('type_of_request')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
