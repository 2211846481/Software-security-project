<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <--- تأكد من استيراد DB
use Illuminate\Support\Str; // <--- تأكد من استيراد Str

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        // استخدام DB::table لسهولة إدخال البيانات مباشرة
        DB::table('documents')->insert([
            'agreement_id' => 1, // افترض أن هناك اتفاقية برقم 1
            'file_name' => 'تقرير_مالي_2024.pdf',
            'file_content' => Str::random(500), // نص عشوائي يمثل محتوى الملف
            'file_type' => 'application/pdf',
            'file_size' => 123456, // حجم الملف بالبايت
            'uploaded_by_user_id' => 1, // افترض أن هناك مستخدم برقم 1
            'description' => 'التقرير المالي للربع الأول من عام 2024',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('documents')->insert([
            'agreement_id' => 1,
            'file_name' => 'صورة_منتج_جديد.jpg',
            'file_content' => Str::random(800),
            'file_type' => 'image/jpeg',
            'file_size' => 567890,
            'uploaded_by_user_id' => 2, // افترض أن هناك مستخدم برقم 2
            'description' => 'صورة دعائية للمنتج الجديد',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // يمكنك إضافة المزيد من البيانات الوهمية هنا...
    }
}