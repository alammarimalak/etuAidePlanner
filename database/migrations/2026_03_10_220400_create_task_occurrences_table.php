<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_occurrences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->timestamp('scheduled_at');
            $table->enum('status', ['pending', 'in_progress', 'review', 'done'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->boolean('is_override')->default(false);
            $table->timestamp('original_scheduled_at')->nullable();
            $table->timestamps();

            $table->index(['task_id', 'scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_occurrences');
    }
};
