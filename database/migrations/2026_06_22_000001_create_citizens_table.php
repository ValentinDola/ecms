<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citizens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ref_no')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name');
            $table->date('date_of_birth')->nullable();
            $table->string('nationality')->default('Togolese');
            $table->string('passport_number')->unique();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address_in_ghana')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->date('registration_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('full_name');
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citizens');
    }
};
