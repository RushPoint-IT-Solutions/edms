<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentSignaturePositionsTable extends Migration
{
    public function up()
    {
        Schema::create('document_signature_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('change_request_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('page_number')->nullable();
            $table->decimal('x_position', 10, 5)->nullable();
            $table->decimal('y_position', 10, 5)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_signature_positions');
    }
}
