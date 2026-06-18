<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemorandumDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('memorandum_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('memorandum_id')->nullable();
            $table->integer('document_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('memorandum_documents');
    }
}
