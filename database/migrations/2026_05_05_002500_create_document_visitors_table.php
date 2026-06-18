<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentVisitorsTable extends Migration
{
    public function up()
    {
        Schema::create('document_visitors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_visitors');
    }
}
