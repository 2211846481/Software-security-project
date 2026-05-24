<?php

    namespace Database\Seeders;

    use Illuminate\Database\Seeder;
    use App\Models\Agreement;
    use App\Models\PartnerInstitution;

    class AgreementPartnerSeeder extends Seeder
    {
        /**
         * Run the database seeds to attach partners to agreements.
         */
        public function run(): void
        {
            // الحصول على بعض الاتفاقيات والمؤسسات الشريكة التي تم إنشاؤها مسبقًا
            // يمكنك تعديل الأرقام حسب عدد السجلات التي تريد ربطها
            $agreement1 = Agreement::find(1);
            $agreement2 = Agreement::find(2);
            $agreement3 = Agreement::find(3);
            $agreement4 = Agreement::find(4);

            $partner1 = PartnerInstitution::find(1);
            $partner2 = PartnerInstitution::find(2);
            $partner3 = PartnerInstitution::find(3);

            // التحقق من وجود السجلات قبل محاولة ربطها
            if ($agreement1 && $partner1) {
                // ربط الاتفاقية الأولى بالمؤسسة الشريكة الأولى
                $agreement1->partners()->attach($partner1->id);
            }

            if ($agreement2 && $partner2) {
                // ربط الاتفاقية الثانية بالمؤسسة الشريكة الثانية
                $agreement2->partners()->attach($partner2->id);
            }

            if ($agreement3 && $partner3) {
                // ربط الاتفاقية الثالثة بالمؤسسة الشريكة الثالثة
                $agreement3->partners()->attach($partner3->id);
            }

            if ($agreement4 && $partner1 && $partner2) {
                // ربط الاتفاقية الرابعة بأكثر من مؤسسة شريكة
                $agreement4->partners()->attach([$partner1->id, $partner2->id]);
            }
        }
    }
    