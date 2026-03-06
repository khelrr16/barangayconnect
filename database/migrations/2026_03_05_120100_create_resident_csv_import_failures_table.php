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
        Schema::create('resident_csv_import_failures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_csv_import_id')->constrained('resident_csv_imports')->cascadeOnDelete();
            $table->unsignedInteger('row_number');
            $table->json('payload')->nullable();
            $table->text('error_message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resident_csv_import_failures');
    }
};
