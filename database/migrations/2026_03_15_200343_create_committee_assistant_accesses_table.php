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
        Schema::create('committee_assistant_accesses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('committee_head_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('assistant_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('committee_slug');

            // Assistant can only be assigned to one committee head at a time.
            $table->unique('assistant_user_id', 'caa_assistant_unique');
            $table->unique(['committee_head_id', 'assistant_user_id'], 'caa_head_assistant_unique');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('committee_assistant_accesses');
    }
};
