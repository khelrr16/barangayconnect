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
        Schema::create('infants', function (Blueprint $table) {
            $table->id();
            $table->date('birthday');
            $table->string('family_serial_number')->unique(); // Unique identifier for FHSIS
            $table->string('name');
            $table->enum('sex', ['Male', 'Female']);
            $table->string('mother_name')->nullable();
            $table->foreignId('household_id')->nullable()->constrained('households');
            $table->foreignId('foreign_household_id')->nullable()->constrained('foreign_households');
            $table->integer('cpab')->nullable();
            
            $table->date('breastfeed_after_birth')->nullable();
            $table->date('iron_1')->nullable();
            $table->date('iron_2')->nullable();
            $table->date('iron_3')->nullable();
            $table->enum('breastfeed_exclusively', ['Yes', 'No'])->nullable();
            $table->date('breastfeed_exclusively_date')->nullable();

            $table->enum('complementary_feeding', ['Yes', 'No'])->nullable();
            $table->integer('complementary_feeding_2')->nullable();
            $table->date('vitamin_a')->nullable();
            $table->date('mnp_start')->nullable();
            $table->date('mnp_end')->nullable();
            $table->date('fic')->nullable();
            $table->date('cic')->nullable();
            $table->integer('malnutrition_type')->nullable();
            $table->string('status')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infants');
    }
};
