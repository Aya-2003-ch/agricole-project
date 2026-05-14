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
        Schema::table('consultations', function (Blueprint $table) {
            // إضافة رابط الحيوان
            // نجعله nullable في البداية إذا كان لديك بيانات قديمة في الجدول
            $table->foreignId('animal_id')
                  ->after('eleveur_id') // وضعه بعد معرف المربي للترتيب
                  ->nullable() 
                  ->constrained('animals')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropForeign(['animal_id']);
            $table->dropColumn('animal_id');
        });
    }
};