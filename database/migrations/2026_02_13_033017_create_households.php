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
        Schema::create('households', function (Blueprint $table) {
            $table->id();
            $table->integer('block');
            $table->integer('lot');
            $table->string('unit', 3)->nullable();
            $table->string('street');
            $table->string('subdivision');
            $table->string('pet_count')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['block', 'lot', 'unit', 'street', 'subdivision'], 'unique_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('households');
    }
};
