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
            // إضافة حقل موافقة المربي على الموعد المقترح
            $table->boolean('is_accepted_by_eleveur')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            // حذف الحقل في حال التراجع عن Migration
            $table->dropColumn('is_accepted_by_eleveur');
        });
    }
};
