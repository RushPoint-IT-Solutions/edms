<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersCopy1Table extends Migration
{
    public function up()
    {
        Schema::create('users_copy1', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->integer('department_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->string('role')->nullable();
            $table->string('status')->nullable();
            $table->string('audit_role')->nullable();
            $table->text('google_id')->nullable();
            $table->text('goolge_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users_copy1');
    }
}
