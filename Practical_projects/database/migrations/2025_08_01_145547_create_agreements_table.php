<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agreements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('reference_code')->unique();
            $table->date('signing_date');
            $table->date('effective_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('description')->nullable();
            
            // تم تعديل هذا السطر: استخدام 'integer' بدلاً من 'string'
            // والمنطق الآن سيحدد القيمة في الكنترولر، لذلك تم حذف القيمة الافتراضية.
            $table->integer('status')->nullable(); 
            
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->foreignId('agreement_type_id')->nullable()->constrained('agreement_types')->onDelete('set null');
            
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agreements');
    }
};
