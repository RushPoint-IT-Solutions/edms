<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRevisionCountAndSnapshots extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('change_requests', 'revision_count')) {
            Schema::table('change_requests', function (Blueprint $table) {
                $table->unsignedInteger('revision_count')->default(0)->after('revision');
            });
        }

        Schema::create('change_request_revisions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('change_request_id');
            $table->unsignedInteger('revision_number')->default(1);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('privacy')->nullable();
            $table->string('status')->nullable();
            $table->text('file')->nullable();
            $table->text('remarks')->nullable();
            $table->date('due_date')->nullable();
            $table->json('approvers_snapshot')->nullable();
            $table->json('departments_snapshot')->nullable();
            $table->json('document_types_snapshot')->nullable();
            $table->unsignedInteger('submitted_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('change_request_revisions');

        Schema::table('change_requests', function (Blueprint $table) {
            $table->dropColumn('revision_count');
        });
    }
}