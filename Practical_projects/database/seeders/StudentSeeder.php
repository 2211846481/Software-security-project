<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student; // استدعاء الموديل Student

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الطريقة الأولى: استخدام create() لإضافة سجل واحد أو أكثر
        Student::create([
            'name' => 'أحمد علي',
            'email' => 'ahmed.ali@example.com',
            'age' => 20,
            'registration_number' => 'STU001',
        ]);

        Student::create([
            'name' => 'فاطمة محمد',
            'email' => 'fatma.mohamed@example.com',
            'age' => 21,
            'registration_number' => 'STU002',
        ]);

        Student::create([
            'name' => 'خالد محمود',
            'email' => 'khaled.mahmoud@example.com',
            'age' => 19,
            'registration_number' => 'STU003',
        ]);

        // الطريقة الثانية: استخدام factory() إذا كنت قد أعددت مصنعًا (factory) للموديل
        // هذا مفيد جدًا لإنشاء عدد كبير من البيانات الوهمية
        // Student::factory()->count(10)->create(); // لإضافة 10 طلاب باستخدام المصنع

        // يمكنك إضافة المزيد من الطلاب هنا بنفس الطريقة
    }
}