<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'discipline_chair' => [
                'name' => 'Discipline Chair',
                'email' => 'discipline@spup.edu.ph',
                'password' => 'discipline2026',
                'employee_id' => 'DC-001'
            ],
            'principal' => [
                'name' => 'Principal',
                'email' => 'principal@spup.edu.ph',
                'password' => 'principal2026',
                'employee_id' => 'PR-001'
            ],
            'assistant_principal' => [
                'name' => 'Assistant Principal',
                'email' => 'assistant@spup.edu.ph',
                'password' => 'assistant2026',
                'employee_id' => 'AP-001'
            ],
            'adviser' => [
                'name' => 'Adviser',
                'email' => 'adviser@spup.edu.ph',
                'password' => 'adviser2026',
                'employee_id' => 'AD-001'
            ],
            'parent' => [
                'name' => 'Parent',
                'email' => 'parent@spup.edu.ph',
                'password' => 'parent2026',
                'employee_id' => 'PA-001'
            ],
            'student' => [
                'name' => 'Juan Dela Cruz',
                'email' => 'student@spup.edu.ph',
                'password' => 'student2026',
                'employee_id' => '2025-00124'
            ],
        ];

        foreach ($roles as $roleName => $userData) {
            $role = Role::where('name', $roleName)->first();
            
            if ($role) {
                User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                    'role_id' => $role->id,
                    'employee_id' => $userData['employee_id'],
                    'email_verified_at' => now(),
                ]);
            }
        }
    }
}
