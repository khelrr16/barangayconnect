<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('extension_name')->nullable();
            $table->string('sex');
            $table->date('birthday');
            $table->string('birthplace');
            $table->string('religion');
            $table->string('citizenship');
            $table->string('civil_status');
            $table->string('contact_number');
            $table->string('email');
            $table->string('ownership');
            $table->string('registered_voter');
            $table->string('precinct_number')->nullable();
            $table->foreignId('household_id')->constrained('households');
            $table->integer('residence_since');
            $table->string('role');
            $table->string('educational_attainment');
            $table->string('occupation');
            $table->string('employment_status');
            $table->string('monthly_income');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
