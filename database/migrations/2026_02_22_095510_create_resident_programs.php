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
        Schema::create('resident_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('residents')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
            // $table->year('batch_year');
            // $table->date('date_received')->nullable();
            // $table->decimal('amount_received', 10, 2)->nullable();
            $table->string('status')->default('active');
            $table->text('remarks')->nullable();
            $table->string('encoded_by')->nullable();
            $table->timestamps();

            $table->unique(['resident_id', 'program_id'], 'unique_resident_program');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resident_programs');
    }
};
