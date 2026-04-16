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
      Schema::create('commandes', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->unsignedBigInteger('id_user');
    $table->unsignedBigInteger('livreur_id');

    $table->foreign('id_user')
     ->references('id')->on('users')->onDelete('cascade');

    $table->foreign('livreur_id')
      ->references('id')->on('livreurs')->onDelete('cascade');
    $table->date('date_commande');
    $table->string('statut');
    
    $table->timestamps();
    $table->softDeletes();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};