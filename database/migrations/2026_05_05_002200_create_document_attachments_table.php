<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentAttachmentsTable extends Migration
{
    public function up()
    {
        Schema::create('document_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_id')->nullable();
            $table->string('attachment')->nullable();
            $table->string('type')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_attachments');
    }
}
