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
       Schema::create('agreement_partners', function (Blueprint $table) {
            // المفتاح الأساسي المركب (Primary Key) من المفتاحين الخارجيين
            $table->foreignId('agreement_id')->constrained('agreements')->onDelete('cascade');
            $table->foreignId('partner_institution_id')->constrained('partner_institutions')->onDelete('cascade');

            // جعل الزوج (agreement_id, partner_institution_id) فريدًا لضمان عدم وجود ارتباطات مكررة
            $table->primary(['agreement_id', 'partner_institution_id']);

            $table->timestamps(); // لتتبع وقت الإنشاء والتحديث للارتباط
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agreement_partners');
    }
};
