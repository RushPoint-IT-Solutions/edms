<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObsoleteAttachmentsTable extends Migration
{
    public function up()
    {
        Schema::create('obsolete_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('obsolete_id')->nullable();
            $table->string('attachment')->nullable();
            $table->string('type')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('obsolete_attachments');
    }
}
