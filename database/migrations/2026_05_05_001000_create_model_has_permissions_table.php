<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelHasPermissionsTable extends Migration
{
    public function up()
    {
        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->unsignedInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->primary(['permission_id', 'model_type', 'model_id']);
            $table->index(['model_id', 'model_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('model_has_permissions');
    }
}
