<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. تعطيل فحص المفاتيح الخارجية مؤقتًا
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. استدعاء جميع السيدرات في قائمة واحدة
        // يفضل ترتيب السيدرات بحيث تعبأ الجداول الرئيسية أولاً (مثل الأقسام، أنواع الاتفاقيات)
        // ثم الجداول التي تعتمد عليها (مثل المستخدمين، الاتفاقيات، المستندات، المؤسسات الشريكة)
        $this->call([
            DepartmentSeeder::class, // يجب أن يأتي قبل الجداول التي تعتمد عليه (مثل المستخدمين والاتفاقيات)
            AgreementTypeSeeder::class, // يجب أن يأتي قبل الاتفاقيات
            UserRoleSeeder::class,
            PartnerInstitutionSeeder::class, // يمكن أن يأتي هنا أو بعد الاتفاقيات حسب الحاجة للعلاقة الثنائية
            AgreementSeeder::class, // يعتمد على Departments و AgreementTypes
            AgreementPartnerSeeder::class,
            DocumentSeeder::class, // يعتمد على Agreements، لذا يجب أن يأتي بعده
            StudentSeeder::class, // يمكن أن يكون ترتيبه هنا أو حسب اعتماداته
        ]);

        // 3. إعادة تفعيل فحص المفاتيح الخارجية
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
