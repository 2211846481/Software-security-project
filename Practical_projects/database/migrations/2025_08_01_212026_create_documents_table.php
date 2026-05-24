<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateDocumentsTable extends Migration
{
    /**
     * تشغيل عمليات الهجرة (Migrations).
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            // يمثل العمود 'id' كمفتاح أساسي (Primary Key) يتزايد تلقائياً
            $table->id();
            
            // العمود الذي يربط المستند بالاتفاقية
            $table->unsignedBigInteger('agreement_id');
            
            // اسم الملف الأصلي (مثلاً: 'تقرير_شهري.pdf')
            $table->string('file_name');
            
            // عمود مؤقت لـ file_content. سيتم تعديله لاحقاً.
            // هذا مطلوب لأننا لا نستطيع تعريف LONGBLOB مباشرة في Blueprint
            $table->text('file_content');

            // نوع الملف (مثلاً: 'application/pdf', 'image/jpeg')
            $table->string('file_type', 255);
            
            // حجم الملف بالبايت، nullable تعني أنه اختياري
            $table->unsignedBigInteger('file_size')->nullable();
            
            // العمود الذي يربط المستند بالمستخدم الذي قام بتحميله
            $table->unsignedBigInteger('uploaded_by_user_id');
            
            // وصف اختياري للمستند
            $table->text('description')->nullable();

            // يضيف عمودي created_at و updated_at
            $table->timestamps();
        });

        // هذا هو الجزء الأهم من الحل. 
        // استخدام أمر SQL خام لتعديل العمود file_content
        // إلى LONGBLOB لتمكين تخزين الملفات الكبيرة (حتى 4 جيجابايت).
        DB::statement('ALTER TABLE documents MODIFY file_content LONGBLOB');
    }

    /**
     * التراجع عن عمليات الهجرة (Migrations).
     *
     * @return void
     */
    public function down()
    {
        // تقوم بحذف جدول 'documents' بالكامل عند التراجع عن الهجرة
        Schema::dropIfExists('documents');
    }
}
