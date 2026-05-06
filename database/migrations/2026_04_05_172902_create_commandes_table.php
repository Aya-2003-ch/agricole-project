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
            $table->id(); // الرقم التعريفي للطلب
            
            // الربط مع المستخدمين (المشتري والبائع)
            $table->unsignedBigInteger('sender_id');   // الموزع الذي أرسل الطلب
            $table->unsignedBigInteger('receiver_id'); // الموزع المستلم للطلب (صاحب المنتج)
            
            // الربط مع جدول المنتجات
            $table->unsignedBigInteger('product_id');  // المنتج المطلوب
            
            // تفاصيل الطلب
            $table->integer('quantity');               // الكمية المطلوبة
            $table->string('phone');                   // رقم هاتف التواصل
            $table->text('address');                 // عنوان التوصيل
            
            // الحالة والنوع
            $table->string('status')->default('pending'); // حالة الطلب (pending, accepted, rejected)
            $table->string('order_type')->default('to_distributeur'); // نوع الطلب لتفريقه عن طلبات المربين

            // تعريف المفاتيح الأجنبية (Foreign Keys)
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('produits')->onDelete('cascade');
            
            $table->timestamps();   // تاريخ الإنشاء والتحديث
            $table->softDeletes(); // خاصية الحذف الناعم (لحفظ السجلات المؤرشفة)
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