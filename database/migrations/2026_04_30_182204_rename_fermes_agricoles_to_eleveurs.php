<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   /* public function up(): void
    {
        Schema::table('eleveurs', function (Blueprint $table) {
            Schema::rename('fermes_agricoles', 'eleveurs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eleveurs', function (Blueprint $table) {
            Schema::rename('eleveurs', 'fermes_agricoles');
        });
    }
};
