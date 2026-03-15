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
        Schema::create('blotter_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blotter_id')
                ->constrained('blotter_records')
                ->cascadeOnDelete();

            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type'); // image, pdf, doc, etc.
            $table->integer('file_size')->nullable(); // in bytes
            $table->string('uploaded_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blotter_documents');
    }
};
