<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParentModel;
use App\Models\Student;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class ParentSeeder extends Seeder
{
    public function run(): void
    {
        $parentRole = Role::where('name', 'parent')->first();

        // Create parent accounts linked to the pre-made student accounts
        $studentsWithParents = [
            [
                'student_email' => 'juan.delacruz.student@spup.edu.ph',
                'parent_name' => 'Roberto Dela Cruz',
                'parent_email' => 'roberto.delacruz@spup.edu.ph',
                'parent_phone' => '+63 9123456789',
                'relationship' => 'father',
            ],
            [
                'student_email' => 'maria.santos.student@spup.edu.ph',
                'parent_name' => 'Elena Santos',
                'parent_email' => 'elena.santos@spup.edu.ph',
                'parent_phone' => '+63 9123456790',
                'relationship' => 'mother',
            ],
            [
                'student_email' => 'jose.reyes.student@spup.edu.ph',
                'parent_name' => 'Patricia Reyes',
                'parent_email' => 'patricia.reyes@spup.edu.ph',
                'parent_phone' => '+63 9123456791',
                'relationship' => 'mother',
            ],
            [
                'student_email' => 'anna.cruz.student@spup.edu.ph',
                'parent_name' => 'Fernando Cruz',
                'parent_email' => 'fernando.cruz@spup.edu.ph',
                'parent_phone' => '+63 9123456792',
                'relationship' => 'father',
            ],
            [
                'student_email' => 'carlos.lopez.student@spup.edu.ph',
                'parent_name' => 'Carmen Lopez',
                'parent_email' => 'carmen.lopez@spup.edu.ph',
                'parent_phone' => '+63 9123456793',
                'relationship' => 'mother',
            ],
        ];

        foreach ($studentsWithParents as $data) {
            // Create parent user account
            $parentUser = User::create([
                'name' => $data['parent_name'],
                'email' => $data['parent_email'],
                'password' => Hash::make('password123'),
                'role_id' => $parentRole->id,
                'email_verified_at' => now(),
            ]);

            // Split parent name
            $nameParts = explode(' ', $data['parent_name']);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';

            // Create parent record
            $parent = ParentModel::create([
                'user_id' => $parentUser->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $data['parent_email'],
                'phone' => $data['parent_phone'],
                'address' => 'Tuguegarao City, Cagayan',
                'relationship' => $data['relationship'],
                'status' => 'active'
            ]);

            // Link to the corresponding student
            $student = Student::whereHas('user', function($q) use ($data) {
                $q->where('email', $data['student_email']);
            })->first();
            
            if ($student) {
                $parent->students()->attach($student->id, ['is_primary_contact' => true]);
            }
        }
    }
}
