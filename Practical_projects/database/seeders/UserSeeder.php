<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; 

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Red Team',
            'email' => 'redTeam@gmailcom',
            'password' => bcrypt('RedTeam1'),
        ]);
    }
}