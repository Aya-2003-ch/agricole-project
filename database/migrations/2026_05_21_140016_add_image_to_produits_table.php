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
    Schema::table('produits', function (Blueprint $table) {
        // جعل الحقل nullable في حال كان المنتج بدون صورة
        $table->string('image')->nullable()->after('lib'); 
    });
}

public function down(): void
{
    Schema::table('produits', function (Blueprint $table) {
        $table->dropColumn('image');
    });
}
};
