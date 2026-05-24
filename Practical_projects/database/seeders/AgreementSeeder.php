<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agreement;
use Carbon\Carbon;

class AgreementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Delete all existing agreements to avoid duplicates on re-seeding
        Agreement::truncate();

        // Array of agreements to be seeded
        $agreements = [
            [
                'title' => 'Academic Cooperation Agreement with Al-Andalus University',
                'reference_code' => 'ACAD-2024-001',
                'signing_date' => '2024-01-15',
                'effective_date' => '2024-02-01',
                'expiry_date' => '2027-02-01',
                'description' => 'This agreement aims to enhance academic and research cooperation between the two universities.',
                'department_id' => 2,
                'agreement_type_id' => 1,
                 ],
            [
                'title' => 'Supply and Installation of Laboratory Equipment',
                'reference_code' => 'SUPP-2023-005',
                'signing_date' => '2023-05-10',
                'effective_date' => '2023-06-01',
                'expiry_date' => '2023-12-31',
                'description' => 'Contract for the supply and installation of new laboratory equipment for the Faculty of Science.',
                'department_id' => 3,
                'agreement_type_id' => 2,
               ],
            [
                'title' => 'Software License Agreement for new ERP System',
                'reference_code' => 'SOFT-2025-010',
                'signing_date' => '2025-03-20',
                'effective_date' => '2025-04-01',
                'expiry_date' => '2030-04-01',
                'description' => 'Licensing agreement with a third-party vendor for a new ERP system.',
                'department_id' => 1,
                'agreement_type_id' => 3,
                 ],
            [
                'title' => 'Draft Memorandum of Understanding with InnovateTech',
                'reference_code' => 'MOU-2025-001',
                'signing_date' => '2025-07-25',
                'effective_date' => '2025-08-10',
                'expiry_date' => '2026-08-10',
                'description' => 'A draft MOU for a potential future collaboration. Not yet signed.',
                'department_id' => 2,
                'agreement_type_id' => 1,
                 ],
        ];

        foreach ($agreements as $agreement) {
            $effectiveDate = Carbon::parse($agreement['effective_date'])->startOfDay();
            $expiryDate = Carbon::parse($agreement['expiry_date'])->startOfDay();
            $currentDate = Carbon::now()->startOfDay();

            // Determine the status based on dates
            $status = 2; // Default status: Draft
            if ($currentDate->isBetween($effectiveDate, $expiryDate, true)) {
                $status = 1; // 1 = Active
            } elseif ($currentDate->gt($expiryDate)) {
                $status = 0; // 0 = Expired
            }
            
            // Add the determined status to the agreement data
            $agreement['status'] = $status;
            
           

            // Create the agreement record in the database
            Agreement::create($agreement);
        }
    }
}
