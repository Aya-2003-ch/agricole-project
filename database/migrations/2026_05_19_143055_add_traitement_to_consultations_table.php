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
        
            $table->text('traitement')->nullable()->after('diagnostique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            // للتراجع عن الهجرة وحذف الحقول إذا لزم الأمر
            $table->dropColumn([ 'traitement']);
        });
    }
};