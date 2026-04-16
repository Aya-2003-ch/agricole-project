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
      Schema::create('stores', function (Blueprint $table) {
    $table->bigIncrements('id');

    $table->unsignedBigInteger('produit_id');
    $table->unsignedBigInteger('distributeur_id');

    $table->integer('quantite');
    $table->date('date_exp');
    $table->decimal('prix', 8,2);
    $table->foreign('produit_id')->references('id')->on('produits')->onDelete('cascade');
    $table->foreign('distributeur_id')->references('id')->on('distributeurs')->onDelete('cascade');
    $table->timestamps();
    $table->softDeletes();
   
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produit_agris');
    }
};
