<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // تعديل الحقل ليحتوي على الدور الجديد
            $table->enum('role', ['distributeur', 'veterinaire', 'eleveur', 'admin'])
                  ->default('eleveur') // أو الدور الافتراضي الذي تفضله
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // في حال التراجع، نعود للأدوار الثلاثة القديمة
            $table->enum('role', ['distributeur', 'veterinaire', 'eleveur'])->change();
        });
    }
};