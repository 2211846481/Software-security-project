<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. إنشاء مستخدم افتراضي ثابت ومضمون لتجربة تسجيل الدخول السريع
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('Password123!'), // كلمة مرور قوية للتجربة الأمنية
        ]);

        // 2. توليد 10 مستخدمين عشوائيين إضافيين عبر الـ Factory لتنويع البيانات
        User::factory(10)->create();
    }
}