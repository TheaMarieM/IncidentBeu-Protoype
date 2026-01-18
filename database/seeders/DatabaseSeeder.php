<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles first
        $this->call(RoleSeeder::class);
        
        // Seed violations, clauses, and sanctions
        $this->call(ViolationSeeder::class);

        // Seed predefined users
        $this->call(UserSeeder::class);
        
        // Seed sample student
        $this->call(StudentSeeder::class);
        
        // Seed parent accounts
        $this->call(ParentSeeder::class);

        // Seed comprehensive data with multiple students, parents, and incidents
        $this->call(ComprehensiveDataSeeder::class);
    }
}
