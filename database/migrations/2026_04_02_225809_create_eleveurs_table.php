<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('fermes_agricoles', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('nom');
        $table->string('localisation');
        
        // --- حطيهم هنا داخل الأقواس ---
        $table->double('latitude')->nullable();
        $table->double('longitude')->nullable();
        // ----------------------------

        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fermes_agricoles');
    }
};
