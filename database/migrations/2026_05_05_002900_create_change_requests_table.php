<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangeRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('change_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('document_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('office_id')->nullable();
            $table->text('indicate_clause')->nullable();
            $table->text('indicate_changes')->nullable();
            $table->date('created_at')->nullable();
            $table->date('updated_at')->nullable();
            $table->string('status')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('level')->nullable();
            $table->string('control_code')->nullable();
            $table->string('title')->nullable();
            $table->integer('revision')->nullable();
            $table->text('file')->nullable();
            $table->string('description')->nullable();
            $table->string('category')->nullable();
            $table->string('privacy')->nullable();
            $table->string('request_status')->nullable();
            $table->string('request_type')->nullable();
            $table->string('is_draft')->nullable();
            $table->text('remarks')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->dateTime('publish_at')->nullable();
            $table->integer('revision_count')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('change_requests');
    }
}
