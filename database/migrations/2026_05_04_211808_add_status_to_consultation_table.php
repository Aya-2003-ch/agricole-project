<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint; // تأكد من وجود هذا السطر
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // تم تغيير Table بـ Blueprint هنا
        Schema::table('consultations', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('eleveur_id');
        });
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};