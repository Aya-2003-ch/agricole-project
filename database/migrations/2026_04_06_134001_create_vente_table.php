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
       Schema::create('ventes', function (Blueprint $table) {
    $table->bigIncrements('id');

    $table->unsignedBigInteger('commande_id');
    $table->unsignedBigInteger('livreur_id');
    $table->unsignedBigInteger('store_id');

    $table->foreign('commande_id')->references('id')->on('commandes')->onDelete('cascade');
    $table->foreign('livreur_id')->references('id')->on('livreurs')->onDelete('cascade');
    $table->foreign('stores_id')->references('id')->on('stores')->onDelete('cascade');

    $table->integer('qte');
    $table->decimal('prix', 10, 2);

    $table->timestamps();
    $table->softDeletes();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vente');
    }
};
