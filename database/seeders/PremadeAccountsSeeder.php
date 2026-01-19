<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\Models\ParentModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PremadeAccountsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('student_parent')->truncate();
        DB::table('parents')->truncate();
        DB::table('students')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Create Discipline Chair
        $disciplineRole = Role::where('name', 'discipline_chair')->first();
        if ($disciplineRole) {
            User::create([
                'name' => 'Discipline Chair',
                'email' => 'discipline@spup.edu.ph',
                'password' => Hash::make('discipline2026'),
                'role_id' => $disciplineRole->id,
                'employee_id' => 'DC-001',
                'email_verified_at' => now(),
            ]);
        }

        // Create Principal
        $principalRole = Role::where('name', 'principal')->first();
        if ($principalRole) {
            User::create([
                'name' => 'Principal',
                'email' => 'principal@spup.edu.ph',
                'password' => Hash::make('principal2026'),
                'role_id' => $principalRole->id,
                'employee_id' => 'PR-001',
                'email_verified_at' => now(),
            ]);
        }

        // Create Assistant Principal
        $assistantRole = Role::where('name', 'assistant_principal')->first();
        if ($assistantRole) {
            User::create([
                'name' => 'Assistant Principal',
                'email' => 'assistant@spup.edu.ph',
                'password' => Hash::make('assistant2026'),
                'role_id' => $assistantRole->id,
                'employee_id' => 'AP-001',
                'email_verified_at' => now(),
            ]);
        }

        // Create Class Advisers
        $adviserRole = Role::where('name', 'adviser')->first();
        $adviserAccounts = [
            [
                'name' => 'Mr. Juan Dela Cruz',
                'email' => 'juan.delacruz@spup.edu.ph',
                'employee_id' => 'ADV-001',
                'grade_level' => '7',
                'section' => 'St. Matthew'
            ],
            [
                'name' => 'Ms. Maria Clara',
                'email' => 'maria.clara@spup.edu.ph',
                'employee_id' => 'ADV-002',
                'grade_level' => '8',
                'section' => 'St. Mark'
            ],
            [
                'name' => 'Mr. Jose Rizal',
                'email' => 'jose.rizal@spup.edu.ph',
                'employee_id' => 'ADV-003',
                'grade_level' => '9',
                'section' => 'St. Luke'
            ],
            [
                'name' => 'Ms. Gabriela Silang',
                'email' => 'gabriela.silang@spup.edu.ph',
                'employee_id' => 'ADV-004',
                'grade_level' => '10',
                'section' => 'St. John'
            ],
            [
                'name' => 'Mr. Andres Bonifacio',
                'email' => 'andres.bonifacio@spup.edu.ph',
                'employee_id' => 'ADV-005',
                'grade_level' => '11',
                'section' => 'St. Paul'
            ],
        ];

        $adviserUsers = [];
        if ($adviserRole) {
            foreach ($adviserAccounts as $adviserData) {
                $adviserUsers[$adviserData['section']] = User::create([
                    'name' => $adviserData['name'],
                    'email' => $adviserData['email'],
                    'password' => Hash::make('password123'),
                    'role_id' => $adviserRole->id,
                    'employee_id' => $adviserData['employee_id'],
                    'grade_level' => $adviserData['grade_level'],
                    'section' => $adviserData['section'],
                    'email_verified_at' => now(),
                ]);
            }
        }

        // Create Students
        $studentRole = Role::where('name', 'student')->first();
        $studentAccounts = [
            [
                'name' => 'Juan Dela Cruz',
                'email' => 'juan.delacruz.student@spup.edu.ph',
                'student_id' => '2025-00124',
                'grade_level' => '7',
                'section' => 'St. Matthew'
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria.santos.student@spup.edu.ph',
                'student_id' => '2025-00125',
                'grade_level' => '8',
                'section' => 'St. Mark'
            ],
            [
                'name' => 'Jose Reyes',
                'email' => 'jose.reyes.student@spup.edu.ph',
                'student_id' => '2025-00126',
                'grade_level' => '9',
                'section' => 'St. Luke'
            ],
            [
                'name' => 'Anna Cruz',
                'email' => 'anna.cruz.student@spup.edu.ph',
                'student_id' => '2025-00127',
                'grade_level' => '10',
                'section' => 'St. John'
            ],
            [
                'name' => 'Carlos Lopez',
                'email' => 'carlos.lopez.student@spup.edu.ph',
                'student_id' => '2025-00128',
                'grade_level' => '11',
                'section' => 'St. Paul'
            ],
        ];

        $studentUsers = [];
        if ($studentRole) {
            foreach ($studentAccounts as $studentData) {
                $names = explode(' ', $studentData['name']);
                $firstName = $names[0];
                $lastName = end($names);

                $studentUser = User::create([
                    'name' => $studentData['name'],
                    'email' => $studentData['email'],
                    'password' => Hash::make('student2026'),
                    'role_id' => $studentRole->id,
                    'employee_id' => $studentData['student_id'],
                    'grade_level' => $studentData['grade_level'],
                    'section' => $studentData['section'],
                    'email_verified_at' => now(),
                ]);

                // Create Student record
                $student = Student::create([
                    'user_id' => $studentUser->id,
                    'student_id' => $studentData['student_id'],
                    'email' => $studentData['email'],
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'date_of_birth' => now()->subYears(15),
                    'grade_level' => $studentData['grade_level'],
                    'section' => $studentData['section'],
                    'adviser_id' => isset($adviserUsers[$studentData['section']]) ? $adviserUsers[$studentData['section']]->id : null,
                    'status' => 'active',
                ]);

                $studentUsers[$studentData['student_id']] = [
                    'user' => $studentUser,
                    'student' => $student
                ];
            }
        }

        // Create Parents and link to students
        $parentRole = Role::where('name', 'parent')->first();
        $parentAccounts = [
            [
                'name' => 'Roberto Dela Cruz',
                'email' => 'roberto.delacruz@spup.edu.ph',
                'student_id' => '2025-00124' // Juan Dela Cruz
            ],
            [
                'name' => 'Elena Santos',
                'email' => 'elena.santos@spup.edu.ph',
                'student_id' => '2025-00125' // Maria Santos
            ],
            [
                'name' => 'Patricia Reyes',
                'email' => 'patricia.reyes@spup.edu.ph',
                'student_id' => '2025-00126' // Jose Reyes
            ],
            [
                'name' => 'Fernando Cruz',
                'email' => 'fernando.cruz@spup.edu.ph',
                'student_id' => '2025-00127' // Anna Cruz
            ],
            [
                'name' => 'Carmen Lopez',
                'email' => 'carmen.lopez@spup.edu.ph',
                'student_id' => '2025-00128' // Carlos Lopez
            ],
        ];

        if ($parentRole) {
            foreach ($parentAccounts as $parentData) {
                $names = explode(' ', $parentData['name']);
                $firstName = $names[0];
                $lastName = end($names);

                $parentUser = User::create([
                    'name' => $parentData['name'],
                    'email' => $parentData['email'],
                    'password' => Hash::make('password123'),
                    'role_id' => $parentRole->id,
                    'email_verified_at' => now(),
                ]);

                // Create ParentModel record
                $parent = ParentModel::create([
                    'user_id' => $parentUser->id,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'relationship' => 'Parent/Guardian',
                    'email' => $parentData['email'],
                    'phone' => '09XXXXXXXXX',
                    'status' => 'active',
                ]);

                // Link parent to student
                if (isset($studentUsers[$parentData['student_id']])) {
                    $parent->students()->attach(
                        $studentUsers[$parentData['student_id']]['student']->id,
                        ['is_primary_contact' => true]
                    );
                }
            }
        }
    }
}
