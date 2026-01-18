<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'discipline_chair',
                'description' => 'Discipline Chairperson - Manages overall conduct records and behavioral incidents'
            ],
            [
                'name' => 'principal',
                'description' => 'Principal - Approves violation reports and accesses analytics'
            ],
            [
                'name' => 'assistant_principal',
                'description' => 'Assistant Principal - Accesses analytics and supports principal'
            ],
            [
                'name' => 'adviser',
                'description' => 'Classroom Adviser - Manages student and parent records for advisees'
            ],
            [
                'name' => 'parent',
                'description' => 'Parent/Guardian - Views attendance records of their children'
            ],
            [
                'name' => 'student',
                'description' => 'Student - Views own attendance, incidents, and profile'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
