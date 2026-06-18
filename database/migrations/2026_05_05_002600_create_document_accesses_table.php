<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentAccessesTable extends Migration
{
    public function up()
    {
        Schema::create('document_accesses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attachment_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->date('expiration_date')->nullable();
            $table->date('created_at')->nullable();
            $table->date('updated_at')->nullable();
            $table->integer('action_by')->nullable();
            $table->integer('stamp')->nullable();
            $table->integer('copy_request_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_accesses');
    }
}
