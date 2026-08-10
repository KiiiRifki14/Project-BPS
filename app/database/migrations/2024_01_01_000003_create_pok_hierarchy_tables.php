<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fiscal_year_id')->constrained('fiscal_years')->onDelete('cascade');
            $table->string('code', 20);   // e.g. GG.2902
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('outputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
            $table->string('code', 20);   // e.g. BMA, FAN
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('sub_outputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('output_id')->constrained('outputs')->onDelete('cascade');
            $table->string('code', 30);   // e.g. BMA.004, BMA.006, FAN.ZZ1
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_output_id')->constrained('sub_outputs')->onDelete('cascade');
            $table->string('code', 20);   // e.g. 005, 051, 052, 523, 530
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('sub_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('component_id')->constrained('components')->onDelete('cascade');
            $table->string('code', 20);   // e.g. 005.0A, 005.0B, 530.0B
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_component_id')->constrained('sub_components')->onDelete('cascade');
            $table->string('code', 10);   // e.g. 521211, 521213, 524114
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->string('code', 10);   // e.g. 001366, 001211
            $table->string('name');
            $table->decimal('pagu', 15, 2)->default(0);
            $table->enum('verification_status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');
            $table->text('rejection_note')->nullable();
            $table->timestamps();
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->string('file_name');          // Original filename shown to user
            $table->string('stored_file_name');   // UUID-based name on disk
            $table->string('file_path');          // Relative path in private storage
            $table->unsignedBigInteger('file_size');
            $table->string('file_type', 10);      // pdf, jpg, jpeg, png
            $table->foreignId('uploaded_by_user_id')->constrained('users')->onDelete('cascade');
            $table->string('label')->nullable();  // e.g. "BAPP Honor", "Kuitansi"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
        Schema::dropIfExists('items');
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('sub_components');
        Schema::dropIfExists('components');
        Schema::dropIfExists('sub_outputs');
        Schema::dropIfExists('outputs');
        Schema::dropIfExists('programs');
    }
};
