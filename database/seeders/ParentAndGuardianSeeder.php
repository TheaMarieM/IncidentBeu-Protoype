<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\Models\ParentModel;
use Faker\Factory as Faker;

class ParentAndGuardianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('en_PH');

        // 1. Get Parent Role
        $parentRole = Role::firstOrCreate(['name' => 'parent'], ['description' => 'Parent or Guardian']);

        // 2. Get students who don't have parents yet (or just all students)
        // Ideally we fetch the students we just created. 
        // We can identify them by checking if they don't have parents in pivot table.
        $students = Student::whereDoesntHave('parents')->get();

        if ($students->isEmpty()) {
            $this->command->info("No students found without parents. Checking all students...");
            $students = Student::all();
        }

        $this->command->info("Assigning parents to " . $students->count() . " students...");

        foreach ($students as $index => $student) {
            // Simulate that siblings might share a parent (every 3rd student shares parent with previous?)
            // For simplicity in this request, let's just give each student 1 unique parent 
            // OR randomly assign existing parents?
            // "assign parents on the students that u created and put it in the database" -> implies creating new ones.

            // Construct parent name based on student name to look realistic
            $lastName = $student->last_name;
            $fatherName = $faker->firstNameMale;
            $email = strtolower($fatherName . '.' . $lastName . '@spup.edu.ph');
            
            // Check if parent with this email exists (to avoid duplicate users)
            if (ParentModel::where('email', $email)->exists()) {
                 $email = strtolower($fatherName . '.' . $lastName . rand(1,99) . '@spup.edu.ph');
            }

            // 3. Create Parent Model Profile
            $parent = ParentModel::create([
                'first_name' => $fatherName,
                'last_name' => $lastName,
                'relationship' => 'Father',
                'email' => $email,
                'phone' => '09' . rand(100000000, 999999999), 
                'address' => $student->address ?? $faker->address,
                'status' => 'active',
            ]);

            // 4. Link Parent to Student (Primary Contact)
            // Use attach to verify: $parent->students()->attach($student->id, ['is_primary_contact' => true]);
            // But checking previous code:
            $parent->students()->attach($student->id, ['is_primary_contact' => true]);

            // 5. Create Parent User Account (for Login)
            // Check if user exists first
            if (!User::where('email', $email)->exists()) {
                User::create([
                    'name' => $fatherName . ' ' . $lastName,
                    'email' => $email,
                    'password' => Hash::make('password123'),
                    'role_id' => $parentRole->id,
                    'status' => 'active',
                    'phone' => $parent->phone,
                ]);
            }

            $this->command->info("Created Parent: {$parent->first_name} {$parent->last_name} for Student: {$student->first_name}");
        }
    }
}
