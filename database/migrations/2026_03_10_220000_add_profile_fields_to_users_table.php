<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['student', 'admin'])->default('student')->after('password');
            $table->string('timezone')->nullable()->after('role');
            $table->enum('theme', ['light', 'dark'])->default('light')->after('timezone');
            $table->string('palette')->nullable()->after('theme');
            $table->boolean('notifications_enabled')->default(true)->after('palette');
            $table->timestamp('last_login_at')->nullable()->after('notifications_enabled');
            $table->timestamp('last_activity_at')->nullable()->after('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'timezone',
                'theme',
                'palette',
                'notifications_enabled',
                'last_login_at',
                'last_activity_at',
            ]);
        });
    }
};
