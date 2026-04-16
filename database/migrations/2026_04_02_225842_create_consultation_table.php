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
    Schema::create('consultations', function (Blueprint $table) {
        $table->bigIncrements('id');

        $table->unsignedBigInteger('ferme_id');
        $table->unsignedBigInteger('veterinaire_id');

        $table->date('date_demande');
        $table->date('date_consultation')->nullable();

        $table->text('motif');
        $table->string('degree');
        $table->foreign('ferme_id')->references('id')->on('fermes_agricoles')->onDelete('cascade');
        $table->foreign('veterinaire_id')->references('id')->on('veterinaires')->onDelete('cascade');;
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation');
    }
};
