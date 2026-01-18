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

        // Create pre-made adviser accounts
        $adviserRole = Role::where('name', 'adviser')->first();
        if ($adviserRole) {
            $advisers = [
                [
                    'name' => 'Mr. Juan Dela Cruz',
                    'email' => 'juan.delacruz@spup.edu.ph',
                    'password' => 'password123',
                    'employee_id' => 'ADV-001',
                    'grade_level' => '7',
                    'section' => 'St. Matthew'
                ],
                [
                    'name' => 'Ms. Maria Clara',
                    'email' => 'maria.clara@spup.edu.ph',
                    'password' => 'password123',
                    'employee_id' => 'ADV-002',
                    'grade_level' => '8',
                    'section' => 'St. Mark'
                ],
                [
                    'name' => 'Mr. Jose Rizal',
                    'email' => 'jose.rizal@spup.edu.ph',
                    'password' => 'password123',
                    'employee_id' => 'ADV-003',
                    'grade_level' => '9',
                    'section' => 'St. Luke'
                ],
                [
                    'name' => 'Ms. Gabriela Silang',
                    'email' => 'gabriela.silang@spup.edu.ph',
                    'password' => 'password123',
                    'employee_id' => 'ADV-004',
                    'grade_level' => '10',
                    'section' => 'St. John'
                ],
                [
                    'name' => 'Mr. Andres Bonifacio',
                    'email' => 'andres.bonifacio@spup.edu.ph',
                    'password' => 'password123',
                    'employee_id' => 'ADV-005',
                    'grade_level' => '11',
                    'section' => 'St. Paul'
                ],
            ];

            foreach ($advisers as $adviserData) {
                User::create([
                    'name' => $adviserData['name'],
                    'email' => $adviserData['email'],
                    'password' => Hash::make($adviserData['password']),
                    'role_id' => $adviserRole->id,
                    'employee_id' => $adviserData['employee_id'],
                    'grade_level' => $adviserData['grade_level'],
                    'section' => $adviserData['section'],
                    'email_verified_at' => now(),
                ]);
            }
        }

        // Create a pre-made student account
        $studentRole = Role::where('name', 'student')->first();
        if ($studentRole) {
            // Create one student per section
            $students = [
                [
                    'name' => 'Juan Dela Cruz',
                    'email' => 'juan.delacruz.student@spup.edu.ph',
                    'employee_id' => '2025-00124',
                    'section' => 'St. Matthew'
                ],
                [
                    'name' => 'Maria Santos',
                    'email' => 'maria.santos.student@spup.edu.ph',
                    'employee_id' => '2025-00125',
                    'section' => 'St. Mark'
                ],
                [
                    'name' => 'Jose Reyes',
                    'email' => 'jose.reyes.student@spup.edu.ph',
                    'employee_id' => '2025-00126',
                    'section' => 'St. Luke'
                ],
                [
                    'name' => 'Anna Cruz',
                    'email' => 'anna.cruz.student@spup.edu.ph',
                    'employee_id' => '2025-00127',
                    'section' => 'St. John'
                ],
                [
                    'name' => 'Carlos Lopez',
                    'email' => 'carlos.lopez.student@spup.edu.ph',
                    'employee_id' => '2025-00128',
                    'section' => 'St. Paul'
                ],
            ];

            foreach ($students as $studentData) {
                User::create([
                    'name' => $studentData['name'],
                    'email' => $studentData['email'],
                    'password' => Hash::make('student2026'),
                    'role_id' => $studentRole->id,
                    'employee_id' => $studentData['employee_id'],
                    'email_verified_at' => now(),
                ]);
            }
        }

        // Create a pre-made parent account
        $parentRole = Role::where('name', 'parent')->first();
        if ($parentRole) {
            User::create([
                'name' => 'Parent',
                'email' => 'parent@spup.edu.ph',
                'password' => Hash::make('parent2026'),
                'role_id' => $parentRole->id,
                'employee_id' => 'PA-001',
                'email_verified_at' => now(),
            ]);
        }
    }
}
