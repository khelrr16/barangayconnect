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
        Schema::create('immunizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('infant_id')->constrained('infants');
            $table->foreignId('medicine_id')->constrained('medicines');
            $table->string('dose_number')->nullable();
            $table->date('administration_date');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('immunizations');
    }
};
