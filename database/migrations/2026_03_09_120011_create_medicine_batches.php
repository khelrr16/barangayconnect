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
        Schema::create('medicine_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained('medicines')->onDelete('cascade');

            $table->string('batch_number'); // Ex: Date&BatchNumber (0313265)
            $table->string('manufacturer')->nullable();
            $table->date('expiry_date');
            $table->date('received_date');
            
            $table->integer('quantity_received');
            $table->integer('quantity_remaining');
            $table->integer('quantity_used')->default(0);
            $table->integer('quantity_wasted')->default(0);
            $table->integer('quantity_expired')->default(0);

            $table->string('status')->nullable()->default('active'); //active, inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_batches');
    }
};
