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
        Schema::create('departments', function (Blueprint $table) {
            $table->id(); // هذا ينشئ عمود ID ترقيم تلقائي
            $table->string('name')->unique(); // هذا ينشئ عمود الاسم، ولا يمكن أن يكون فارغًا
            $table->timestamps(); // هذا ينشئ عمودي created_at و updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};