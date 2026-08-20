<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Alur Logika REVISI — Section 2: Add checklist columns to documents table
     */
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->boolean('is_checked')->default(false)->after('label');
            $table->foreignId('checked_by_user_id')->nullable()->after('is_checked')->constrained('users')->nullOnDelete();
            $table->timestamp('checked_at')->nullable()->after('checked_by_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['checked_by_user_id']);
            $table->dropColumn(['is_checked', 'checked_by_user_id', 'checked_at']);
        });
    }
};
