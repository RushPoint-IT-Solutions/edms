<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShareDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('share_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('document_id')->nullable();
            $table->integer('shared_by')->nullable();
            $table->timestamps();
            $table->integer('folder_id')->nullable();
            $table->dateTime('notified_at')->nullable();
            $table->dateTime('seen_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('share_documents');
    }
}
