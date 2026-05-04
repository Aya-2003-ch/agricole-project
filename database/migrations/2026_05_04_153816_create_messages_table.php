<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            // معرف المرسل (سواء كان فلاح أو بيطري)
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            
            // معرف المستقبل
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            
            // نص الرسالة
            $table->text('content');
            
            // حالة القراءة (اختياري)
            $table->boolean('is_read')->default(false);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};