<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assistance_cases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ref_no')->unique();
            $table->string('case_number')->unique();
            $table->uuid('citizen_id')->constrained()->cascadeOnDelete();
            $table->string('case_type');
            $table->string('status')->default('open');
            $table->dateTime('opened_at');
            $table->dateTime('closed_at')->nullable();
            $table->text('description');
            $table->text('actions_taken')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('case_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assistance_cases');
    }
};
