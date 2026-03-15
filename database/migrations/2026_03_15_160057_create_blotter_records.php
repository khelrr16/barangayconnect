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
        Schema::create('blotter_records', function (Blueprint $table) {
            $table->id();

            $table->string('blotter_number')->unique()->comment('Format: BLT-YYYY-XXXX');

            $table->string('complainant_name');
            $table->string('complainant_contact')->nullable();
            $table->text('complainant_address');


            $table->string('respondent_name');
            $table->string('respondent_contact')->nullable();
            $table->text('respondent_address');

            $table->string('case_type');
            $table->date('date_filled');
            $table->text('case_description');

            $table->string('witnesses')->nullable();

            $table->foreignId('assigned_official_id')
                ->nullable()
                ->constrained('officials')
                ->nullOnDelete()
                ->comment('Assigned Lupon Official');

            $table->datetime('hearing_datetime')->nullable();
            $table->string('hearing_venue')->nullable();

            $table->string('status');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamp('filed_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blotter_records');
    }
};
