<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['inactive_student'])->default('inactive_student');
            $table->enum('status', ['open', 'dismissed', 'resolved'])->default('open');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('resolved_at')->nullable();

            $table->index(['admin_id', 'status']);
            $table->index(['subject_user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_alerts');
    }
};
