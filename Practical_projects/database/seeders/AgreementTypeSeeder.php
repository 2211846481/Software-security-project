<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AgreementType;
use Illuminate\Support\Facades\Schema;

class AgreementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add default agreement types
        $types = [
            ['name' => 'Memorandum of Understanding (MoU)', 'description' => 'A non-legally binding understanding between parties.'],
            ['name' => 'Memorandum of Agreement (MoA)', 'description' => 'A more formal agreement than an MoU, often legally binding.'],
            ['name' => 'Exchange Program', 'description' => 'Agreements related to student or academic exchange programs.'],
            ['name' => 'Research Collaboration', 'description' => 'Agreements for collaboration on research projects.'],
            ['name' => 'Service Contract', 'description' => 'Contracts for the provision of services.'],
        ];

        foreach ($types as $type) {
            AgreementType::firstOrCreate($type);
        }
    }
}
