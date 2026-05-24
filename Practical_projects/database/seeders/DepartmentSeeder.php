<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // If you prefer using DB facade
use App\Models\Department; // Using the Department model

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Using the Department model
        Department::create(['name' => 'General Administration']);
        Department::create(['name' => 'International Relations']);
        Department::create(['name' => 'Scientific Research']);
        Department::create(['name' => 'Student Affairs']);
        Department::create(['name' => 'Human Resources']);
        Department::create(['name' => 'Financial Affairs']);
        Department::create(['name' => 'Legal Affairs']);
        // Or you can use the DB facade to insert multiple rows at once
        // DB::table('departments')->insert([
        //     ['name' => 'IT'],
        //     ['name' => 'HR'],
        //     ['name' => 'Finance'],
        //     ['name' => 'Marketing'],
        // ]);
    }
}