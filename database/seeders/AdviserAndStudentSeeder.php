<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use Faker\Factory as Faker;

class AdviserAndStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('en_PH');

        // 1. Get Adviser Role
        $adviserRole = Role::firstOrCreate(['name' => 'adviser'], ['description' => 'Class Adviser']);

        // 2. Define 5 Sections with specific Advisers and Grades
        $sections = [
            [
                'grade' => '7',
                'section' => 'St. Matthew',
                'adviser_name' => 'Juan Dela Cruz',
                'email' => 'juan.delacruz@spup.edu.ph',
                'gender' => 'male'
            ],
            [
                'grade' => '8',
                'section' => 'St. Mark',
                'adviser_name' => 'Maria Clara',
                'email' => 'maria.clara@spup.edu.ph',
                'gender' => 'female'
            ],
            [
                'grade' => '9',
                'section' => 'St. Luke',
                'adviser_name' => 'Jose Rizal',
                'email' => 'jose.rizal@spup.edu.ph',
                'gender' => 'male'
            ],
            [
                'grade' => '10',
                'section' => 'St. John',
                'adviser_name' => 'Gabriela Silang',
                'email' => 'gabriela.silang@spup.edu.ph',
                'gender' => 'female'
            ],
            [
                'grade' => '11',
                'section' => 'St. Paul',
                'adviser_name' => 'Andres Bonifacio',
                'email' => 'andres.bonifacio@spup.edu.ph',
                'gender' => 'male'
            ],
        ];

        // Optional: Clean up existing advisers and students to match "remove names in system" request
        // Be careful not to delete currently logged in user if they are admin, but here we target advisers.
        // We will remove students first to avoid foreign key issues.
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Student::truncate(); 
        // User::where('role_id', $adviserRole->id)->delete();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // Ideally we shouldn't truncate in a live system, but this is a prototype/setup request.
        // Let's just delete students and advisers to be safe and clean.
        
        $adviserIdsToDelete = User::where('role_id', $adviserRole->id)->pluck('id');
        Student::whereIn('adviser_id', $adviserIdsToDelete)->delete(); // Remove students of old advisers
        User::destroy($adviserIdsToDelete); // Remove old advisers


        foreach ($sections as $data) {
            // Create Adviser
            $adviser = User::create([
                'name' => $data['adviser_name'],
                'email' => $data['email'],
                'password' => Hash::make('password123'), // Default password
                'role_id' => $adviserRole->id,
                'status' => 'active',
                'employee_id' => 'EMP-' . rand(1000, 9999),
            ]);

            $this->command->info("Created Adviser: {$adviser->name} ({$data['grade']} - {$data['section']})");

            // Create 5-10 True Students for this section
            $studentCount = rand(5, 10);
            for ($i = 0; $i < $studentCount; $i++) {
                $gender = $faker->randomElement(['male', 'female']);
                $firstName = $gender == 'male' ? $faker->firstNameMale : $faker->firstNameFemale;
                
                Student::create([
                    'student_id' => date('Y') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT),
                    'first_name' => $firstName,
                    'middle_name' => $faker->lastName, // Middle name is usually mother's maiden surname
                    'last_name' => $faker->lastName,
                    'date_of_birth' => $faker->dateTimeBetween('-18 years', '-12 years'),
                    'gender' => $gender,
                    'grade_level' => $data['grade'],
                    'section' => $data['section'],
                    'adviser_id' => $adviser->id,
                    'address' => $faker->address,
                    'status' => 'active',
                ]);
            }
            $this->command->info("  - Added {$studentCount} students.");
        }
    }
}
