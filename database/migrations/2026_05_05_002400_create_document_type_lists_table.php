<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentTypeListsTable extends Migration
{
    public function up()
    {
        Schema::create('document_type_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_id')->nullable();
            $table->integer('type')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_type_lists');
    }
}
