<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreAssessmentsTable extends Migration
{
    public function up()
    {
        Schema::create('pre_assessments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('request_type')->nullable();
            $table->date('effective_date')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('type_of_document')->nullable();
            $table->integer('document_id')->nullable();
            $table->text('change_request')->nullable();
            $table->text('reason_for_changes')->nullable();
            $table->text('link_draft')->nullable();
            $table->date('created_at')->nullable();
            $table->date('updated_at')->nullable();
            $table->string('status')->nullable();
            $table->integer('level')->nullable();
            $table->integer('company_id')->nullable();
            $table->string('control_code')->nullable();
            $table->string('title');
            $table->integer('revision')->nullable();
            $table->string('original_attachment_pdf')->nullable();
            $table->string('original_attachment_soft_copy')->nullable();
            $table->string('pdf_copy')->nullable();
            $table->string('soft_copy')->nullable();
            $table->string('fillable_copy')->nullable();
            $table->text('supporting_documents')->nullable();
            $table->text('indicate_clause')->nullable();
            $table->text('indicate_changes')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pre_assessments');
    }
}
