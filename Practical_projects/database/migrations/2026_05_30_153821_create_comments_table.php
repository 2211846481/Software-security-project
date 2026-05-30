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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            
            // ربط التعليق بالمستخدم من جدول users (علاقة Foreign Key)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // حقل نص التعليق (بيانات نصية طويلة - سنطبق عليها حماية XSS لاحقاً)
            $table->text('comment_text');
            
            // مسار الصورة المخزنة على السيرفر (Nullable لأنه اختياري)
            $table->string('file_path')->nullable();
            
            // نوع الملف الفعلي لضمان الأمان السيبراني ومطابقته (مثل image/png)
            $table->string('file_mime_type')->nullable();
            
            // حجم الملف بالبايت (حقل رقمي لتنويع البيانات ومنع إغراق السيرفر)
            $table->integer('file_size')->nullable();
            
            // التواريخ الافتراضية (وقت إنشاء ونشر التعليق)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};