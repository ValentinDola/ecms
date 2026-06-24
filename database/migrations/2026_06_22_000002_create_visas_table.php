<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('citizen_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ref_no')->unique();
            $table->string('visa_number')->unique();
            $table->string('passport_number');
            $table->string('applicant_first_name');
            $table->string('applicant_last_name');
            $table->string('visa_type');
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->string('status')->default('pending');
            $table->text('purpose_of_visit')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('passport_number');
            $table->index(['applicant_last_name', 'applicant_first_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visas');
    }
};
