<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@culinary-academy.com'],
            [
                'name' => 'Admin User',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create a tutor user
        \App\Models\User::firstOrCreate(
            ['email' => 'tutor@culinary-academy.com'],
            [
                'name' => 'Chef Instructor',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'tutor',
            ]
        );

        // Update existing users to have tutor role (default)
        \App\Models\User::whereNull('role')->update(['role' => 'tutor']);
        
        // Update existing students to have learner role (should be default already)
        \App\Models\Student::whereNull('role')->update(['role' => 'learner']);
        
        $this->command->info('Admin user created: admin@culinary-academy.com / password');
        $this->command->info('Tutor user created: tutor@culinary-academy.com / password');
    }
}
