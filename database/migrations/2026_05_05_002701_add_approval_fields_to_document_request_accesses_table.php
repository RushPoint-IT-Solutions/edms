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
        if (!Schema::hasColumn('document_request_accesses', 'approve_notes')) {
            Schema::table('document_request_accesses', function (Blueprint $table) {
                $table->text('approve_notes')->nullable()->after('reason');
            });
        }

        if (!Schema::hasColumn('document_request_accesses', 'access_until')) {
            Schema::table('document_request_accesses', function (Blueprint $table) {
                $table->date('access_until')->nullable()->after('approve_notes');
            });
        }

        if (!Schema::hasColumn('document_request_accesses', 'decline_reason')) {
            Schema::table('document_request_accesses', function (Blueprint $table) {
                $table->text('decline_reason')->nullable()->after('access_until');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        if (!Schema::hasTable('document_request_accesses')) {
            return;
        }

        Schema::table('document_request_accesses', function (Blueprint $table) {
            if (Schema::hasColumn('document_request_accesses', 'approve_notes')) {
                $table->dropColumn('approve_notes');
            }

            if (Schema::hasColumn('document_request_accesses', 'access_until')) {
                $table->dropColumn('access_until');
            }

            if (Schema::hasColumn('document_request_accesses', 'decline_reason')) {
                $table->dropColumn('decline_reason');
            }
        });
    }
}
