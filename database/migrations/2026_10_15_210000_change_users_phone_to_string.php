<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 32)->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        // Keep phone values intact: international numbers cannot safely revert to integers.
    }
};
