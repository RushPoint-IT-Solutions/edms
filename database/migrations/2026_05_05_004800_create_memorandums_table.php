<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemorandumsTable extends Migration
{
    public function up()
    {
        Schema::create('memorandums', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('department_id')->nullable();
            $table->string('memo_number')->nullable();
            $table->string('title')->nullable();
            $table->date('released_date')->nullable();
            $table->integer('uploaded_by')->nullable();
            $table->text('file_memo')->nullable();
            $table->timestamps();
            $table->integer('document_id')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('memorandums');
    }
}
