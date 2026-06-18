<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrivateDocsVisitorsTable extends Migration
{
    public function up()
    {
        Schema::create('private_docs_visitors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('change_request_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('private_docs_visitors');
    }
}
