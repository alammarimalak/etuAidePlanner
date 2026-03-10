<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('color', 7)->nullable();
            $table->boolean('is_system')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'is_system']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
