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
        Schema::table('distributeurs', function (Blueprint $table) {
            $table->foreignId('user_id')
                  ->nullable() // باش ما يصراش مشكل مع الداتا القديمة
                  ->constrained('users')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distributeurs', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // نحّي العلاقة
            $table->dropColumn('user_id');    // نحّي العمود
        });
    }
};