<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department; // تأكد من استيراد موديل Department
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, make sure you have at least one department to link to
        $department = Department::create(['name' => 'IT Department']);

        // Now, add users and link them to the department
        User::create([
            'name' => 'Viewer User',
            'email' => 'viewer@example.com',
            'password' => Hash::make('password'),
            'role' => 0, // 'Viewer'
            'department_id' => $department->id,
        ]);

        User::create([
            'name' => 'Department User',
            'email' => 'department@example.com',
            'password' => Hash::make('password'),
            'role' => 1, // 'Department User'
            'department_id' => $department->id,
        ]);

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 2, // 'Admin'
            'department_id' => $department->id,
        ]);

        $this->command->info('Users with different roles seeded successfully!');
    }
}