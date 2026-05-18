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
        Schema::table('animals', function (Blueprint $blueprint) {
            // إضافة عمود السن بعد عمود رمز التعريف، وجعله nullable تفادياً للمشاكل
            $blueprint->string('age')->nullable()->after('identification_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animals', function (Blueprint $blueprint) {
            // لحذف العمود في حال التراجع
            $blueprint->dropColumn('age');
        });
    }
};