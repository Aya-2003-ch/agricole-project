<?php
 use Illuminate\Database\Migrations\Migration; 
 use Illuminate\Database\Schema\Blueprint; 
 use Illuminate\Support\Facades\Schema; 

 return new class extends Migration {

 public function up()
{
    Schema::create('consultations', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('eleveur_id');
        $table->unsignedBigInteger('veterinaire_id');

        $table->date('date_demande');
        $table->date('date_consultation')->nullable();

        $table->text('motif');
        $table->string('degree')->nullable();

        // 🔥 المهم
        $table->string('status')->default('pending'); // pending / accepted / rejected

        $table->text('diagnostique')->nullable();

        // ✔️ ربط مع users
        $table->foreign('eleveur_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('veterinaire_id')->references('id')->on('users')->onDelete('cascade');

        $table->timestamps();
    });
     } /** * Reverse the migrations. */ 
     public function down(): void { 
        Schema::dropIfExists('consultation'); 
        } 
        };
      
    
