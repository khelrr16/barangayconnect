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
            $table->string('citizenship');
            $table->string('civil_status');
            $table->string('contact_number');
            $table->string('email');
            $table->string('ownership');
            $table->string('registered_voter');
            $table->string('precinct_number')->nullable();
            $table->integer('residence_since');
            $table->foreignId('household_id')->constrained('households');
            $table->string('role');
            $table->string('educational_attainment');
            $table->string('occupation');
            $table->string('total_income');
            $table->string('benificiary_4ps');
            $table->timestamps();
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
