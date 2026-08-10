<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip_username')->unique()->after('id');
            $table->string('name')->after('nip_username');
            $table->enum('role', ['ADMIN', 'SUPERVISOR', 'OPERATOR', 'BENDAHARA'])->default('OPERATOR')->after('name');
            $table->dropColumn('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nip_username', 'role']);
            $table->string('email')->nullable();
        });
    }
};
