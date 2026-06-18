<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolePermissionsTable extends Migration
{
    public function up()
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('role')->index();
            $table->string('permission_key');
            $table->unique(['role', 'permission_key']);
            $table->string('can_view')->nullable();
            $table->string('can_create')->nullable();
            $table->string('can_edit')->nullable();
            $table->string('can_delete')->nullable();
            $table->string('can_approve')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_permissions');
    }
}
