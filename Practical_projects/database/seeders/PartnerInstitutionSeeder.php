<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PartnerInstitution; // تأكد من استيراد الـ Model

class PartnerInstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PartnerInstitution::create([
            'name' => 'جامعة التكنولوجيا',
            'country' => 'ألمانيا',
            'contact_name' => 'د. هانس مولر',
            'contact_email' => 'h.muller@tech-uni.de',
            'website' => 'https://www.tech-uni.de',
            'sector' => 'جامعة',
        ]);

        PartnerInstitution::create([
            'name' => 'مركز الأبحاث الدولي',
            'country' => 'الولايات المتحدة',
            'contact_name' => 'السيدة جين دو',
            'contact_email' => 'jane.doe@irc.org',
            'website' => 'https://www.irc.org',
            'sector' => 'مركز بحثي',
        ]);

        PartnerInstitution::create([
            'name' => 'مؤسسة التعاون الأفريقي',
            'country' => 'جنوب أفريقيا',
            'contact_name' => 'السيد ماندلا كوبوس',
            'contact_email' => 'mandla.k@african-coop.org',
            'website' => 'https://www.african-coop.org',
            'sector' => 'منظمة غير حكومية',
        ]);

        // يمكنك إضافة المزيد من البيانات الوهمية هنا
    }
}