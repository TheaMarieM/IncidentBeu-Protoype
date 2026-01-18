<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all adviser users with their sections
        $advisers = User::whereHas('role', function ($query) {
            $query->where('name', 'adviser');
        })->get();

        $maleFirstNames = ['Joshua', 'Nikolas', 'John Michael', 'Albert', 'Ernesto', 'Carlos', 'Miguel', 'Rafael', 'Gabriel', 'Sebastian'];
        $femaleFirstNames = ['Joanny', 'Estel', 'Athena', 'Maria', 'Isabella', 'Sophia', 'Emma', 'Olivia', 'Ava', 'Mia'];
        $lastNames = ['Doyle', 'Torp', 'Bosco', 'Rippin', 'Hickle', 'Santos', 'Garcia', 'Reyes', 'Cruz', 'Martinez'];
        $middleNames = ['Anne', 'Rose', 'Grace', 'Mae', 'Joy', 'Luis', 'Jose', 'Ray', 'Lee', 'Kent'];

        $studentIdCounter = 200; // Start from 200 to avoid conflicts

        foreach ($advisers as $adviser) {
            // Create 8-12 students per section
            $studentCount = rand(8, 12);
            
            for ($i = 0; $i < $studentCount; $i++) {
                $gender = rand(0, 1) ? 'male' : 'female';
                $firstName = $gender === 'male' 
                    ? $maleFirstNames[array_rand($maleFirstNames)]
                    : $femaleFirstNames[array_rand($femaleFirstNames)];
                
                Student::create([
                    'student_id' => '2025-' . str_pad($studentIdCounter++, 5, '0', STR_PAD_LEFT),
                    'first_name' => $firstName,
                    'middle_name' => $middleNames[array_rand($middleNames)],
                    'last_name' => $lastNames[array_rand($lastNames)],
                    'date_of_birth' => date('Y-m-d', strtotime('-' . rand(12, 18) . ' years')),
                    'gender' => $gender,
                    'grade_level' => $adviser->grade_level,
                    'section' => $adviser->section,
                    'adviser_id' => $adviser->id,
                    'address' => 'Tuguegarao City, Cagayan',
                    'status' => 'active',
                ]);
            }
        }

        // Create the specific student users (one per section)
        $studentsData = [
            [
                'email' => 'juan.delacruz.student@spup.edu.ph',
                'student_id' => '2025-00124',
                'first_name' => 'Juan',
                'middle_name' => 'Santos',
                'last_name' => 'Dela Cruz',
                'date_of_birth' => '2011-05-15',
                'gender' => 'male',
                'grade_level' => '7',
                'section' => 'St. Matthew',
            ],
            [
                'email' => 'maria.santos.student@spup.edu.ph',
                'student_id' => '2025-00125',
                'first_name' => 'Maria',
                'middle_name' => 'Ciara',
                'last_name' => 'Santos',
                'date_of_birth' => '2010-08-20',
                'gender' => 'female',
                'grade_level' => '8',
                'section' => 'St. Mark',
            ],
            [
                'email' => 'jose.reyes.student@spup.edu.ph',
                'student_id' => '2025-00126',
                'first_name' => 'Jose',
                'middle_name' => 'Miguel',
                'last_name' => 'Reyes',
                'date_of_birth' => '2009-11-10',
                'gender' => 'male',
                'grade_level' => '9',
                'section' => 'St. Luke',
            ],
            [
                'email' => 'anna.cruz.student@spup.edu.ph',
                'student_id' => '2025-00127',
                'first_name' => 'Anna',
                'middle_name' => 'Grace',
                'last_name' => 'Cruz',
                'date_of_birth' => '2008-03-25',
                'gender' => 'female',
                'grade_level' => '10',
                'section' => 'St. John',
            ],
            [
                'email' => 'carlos.lopez.student@spup.edu.ph',
                'student_id' => '2025-00128',
                'first_name' => 'Carlos',
                'middle_name' => 'Antonio',
                'last_name' => 'Lopez',
                'date_of_birth' => '2007-07-18',
                'gender' => 'male',
                'grade_level' => '11',
                'section' => 'St. Paul',
            ],
        ];

        foreach ($studentsData as $data) {
            $adviser = User::where('grade_level', $data['grade_level'])
                ->where('section', $data['section'])
                ->whereHas('role', function($q) {
                    $q->where('name', 'adviser');
                })->first();
            
            $studentUser = User::where('email', $data['email'])->first();
            
            if ($adviser && $studentUser) {
                Student::create([
                    'user_id' => $studentUser->id,
                    'student_id' => $data['student_id'],
                    'first_name' => $data['first_name'],
                    'middle_name' => $data['middle_name'],
                    'last_name' => $data['last_name'],
                    'date_of_birth' => $data['date_of_birth'],
                    'gender' => $data['gender'],
                    'grade_level' => $data['grade_level'],
                    'section' => $data['section'],
                    'adviser_id' => $adviser->id,
                    'address' => 'Tuguegarao City, Cagayan',
                    'email' => $data['email'],
                    'status' => 'active',
                ]);
            }
        }
    }
}
