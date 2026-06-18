<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovalFieldsToDocumentRequestAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('document_request_accesses', function (Blueprint $table) {
            $table->text('approve_notes')->nullable()->after('reason');
            $table->date('access_until')->nullable()->after('approve_notes');
            $table->text('decline_reason')->nullable()->after('access_until');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('document_request_accesses', function (Blueprint $table) {
            $table->dropColumn(['approve_notes', 'access_until', 'decline_reason']);
        });
    }
}
