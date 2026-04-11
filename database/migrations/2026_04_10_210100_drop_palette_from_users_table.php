<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'palette')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('palette');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'palette')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('palette')->nullable()->after('theme');
        });
    }
};
