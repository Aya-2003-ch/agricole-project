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
        // نتحققوا إذا العمود القديم كاين قبل ما نبدلوه، هكذا ما يصرى حتى Error
        if (Schema::hasColumn('commandes', 'fermes_agricoles_id')) {
            Schema::table('commandes', function (Blueprint $table) {
                $table->renameColumn('fermes_agricoles_id', 'id_user');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('commandes', 'id_user')) {
            Schema::table('commandes', function (Blueprint $table) {
               $table->renameColumn('id_user', 'fermes_agricoles_id');  
            });
        }
    }
};