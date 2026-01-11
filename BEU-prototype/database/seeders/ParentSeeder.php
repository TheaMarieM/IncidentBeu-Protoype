<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParentModel;
use App\Models\Student;

class ParentSeeder extends Seeder
{
    public function run(): void
    {
        // Create a parent record linked to email
        $parent = ParentModel::create([
            'first_name' => 'Elena',
            'middle_name' => 'Santos',
            'last_name' => 'Dela Cruz',
            'email' => 'parent@spup.edu.ph',
            'phone' => '+63 9123456789',
            'alternate_phone' => '+63 9987654321',
            'address' => '123 Sample Street, Metro Manila',
            'relationship' => 'mother',
            'status' => 'active'
        ]);

        // Link students to this parent
        // Note: We'll link to the student created in StudentSeeder
        $students = Student::whereIn('student_id', ['2025-00124'])->get();
        
        foreach ($students as $student) {
            $parent->students()->attach($student->id);
        }
    }
}
