<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentRequestAccessesTable extends Migration
{
    public function up()
    {
        Schema::create('document_request_accesses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('change_request_id')->nullable();
            $table->integer('document_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->text('reason')->nullable();
            $table->text('approve_notes')->nullable();
            $table->date('access_until')->nullable();
            $table->text('decline_reason')->nullable();
            $table->date('request_date')->nullable();
            $table->integer('requestor_id');
            $table->integer('status')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_request_accesses');
    }
}
