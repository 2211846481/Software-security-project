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
        Schema::create('partner_institutions', function (Blueprint $table) {
            $table->id(); // INT, PK, auto-increment
            $table->string('name'); // VARCHAR(255)
            $table->string('country', 100); // VARCHAR(100)
            $table->string('contact_name')->nullable(); // VARCHAR(255), يمكن أن يكون فارغًا
            $table->string('contact_email')->nullable(); // VARCHAR(255), يمكن أن يكون فارغًا
            $table->string('website')->nullable(); // VARCHAR(255), يمكن أن يكون فارغًا
            $table->string('sector', 100)->nullable(); // VARCHAR(100), يمكن أن يكون فارغًا
            $table->timestamps(); // creates created_at and updated_at DATETIME columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_institutions');
    }
};
