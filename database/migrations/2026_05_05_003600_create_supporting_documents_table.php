<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportingDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('supporting_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('change_request_id')->nullable();
            $table->text('file')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('supporting_documents');
    }
}
