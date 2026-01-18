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
        // Get the adviser user
        $adviser = User::whereHas('role', function ($query) {
            $query->where('name', 'adviser');
        })->first();

        // Create sample student record linked to the student user
        Student::create([
            'student_id' => '2025-00124',
            'first_name' => 'Juan',
            'middle_name' => 'Santos',
            'last_name' => 'Dela Cruz',
            'date_of_birth' => '2011-05-15',
            'gender' => 'male',
            'grade_level' => '10',
            'section' => 'St. Luke',
            'adviser_id' => $adviser?->id,
            'address' => 'Tuguegarao City, Cagayan',
            'status' => 'active',
        ]);
    }
}
