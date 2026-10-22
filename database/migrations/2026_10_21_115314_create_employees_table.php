<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name',100);
            $table->string('last_name',100);
            $table->string('email')->unique();
            $table->string('phone');
            $table->enum('gender', ['male', 'female', 'other']); // Radio
            $table->json('skills');                               // Checkboxes (JSON array)
            $table->date('date_of_birth');                        // Datepicker
            $table->date('joining_date');                         // Datepicker
            $table->string('avatar')->nullable();                 // File upload path
            
            // Dependent Dropdowns
            $table->foreignId('country_id')->constrained()->restrictOnDelete();
            $table->foreignId('state_id')->constrained()->restrictOnDelete();
            $table->foreignId('city_id')->constrained()->restrictOnDelete();

            $table->enum('status', ['active', 'inactive'])->default('active'); // Dropdown filter
            $table->timestamps();

            // Indexes for fast searching and filtering
            $table->index(['first_name', 'last_name']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};