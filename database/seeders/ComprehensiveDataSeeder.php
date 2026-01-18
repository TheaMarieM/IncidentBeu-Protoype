<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\ParentModel;
use App\Models\AttendanceRecord;
use App\Models\Incident;
use App\Models\ViolationCategory;
use App\Models\User;
use App\Models\Role;

class ComprehensiveDataSeeder extends Seeder
{
    public function run(): void
    {
        // Get advisers by grade level and section
        $advisers = User::whereHas('role', function ($query) {
            $query->where('name', 'adviser');
        })->get()->keyBy(function($adviser) {
            return $adviser->grade_level . '-' . $adviser->section;
        });
        
        // Create multiple students with parents and data
        $studentsData = [
            [
                'student_id' => '2025-00125',
                'first_name' => 'Maria',
                'middle_name' => 'Ciara',
                'last_name' => 'Santos',
                'grade_level' => 8,
                'section' => 'St. Mark',
                'parent_name' => 'Maria Santos',
                'parent_email' => 'maria.santos@example.com',
                'parent_phone' => '+63 9123456790',
                'absences' => 5,
                'tardies' => 3
            ],
            [
                'student_id' => '2025-00126',
                'first_name' => 'John',
                'middle_name' => 'Michael',
                'last_name' => 'Cruz',
                'grade_level' => 9,
                'section' => 'St. Luke',
                'parent_name' => 'Robert Cruz',
                'parent_email' => 'robert.cruz@example.com',
                'parent_phone' => '+63 9123456791',
                'absences' => 8,
                'tardies' => 6
            ],
            [
                'student_id' => '2025-00127',
                'first_name' => 'Anna',
                'middle_name' => 'Grace',
                'last_name' => 'Reyes',
                'grade_level' => 10,
                'section' => 'St. John',
                'parent_name' => 'Patricia Reyes',
                'parent_email' => 'patricia.reyes@example.com',
                'parent_phone' => '+63 9123456792',
                'absences' => 2,
                'tardies' => 1
            ],
            [
                'student_id' => '2025-00128',
                'first_name' => 'Carlos',
                'middle_name' => 'Antonio',
                'last_name' => 'Lopez',
                'grade_level' => 7,
                'section' => 'St. Matthew',
                'parent_name' => 'Fernando Lopez',
                'parent_email' => 'fernando.lopez@example.com',
                'parent_phone' => '+63 9123456793',
                'absences' => 12,
                'tardies' => 8
            ],
            [
                'student_id' => '2025-00129',
                'first_name' => 'Isabella',
                'middle_name' => 'Rose',
                'last_name' => 'Garcia',
                'grade_level' => 11,
                'section' => 'St. Paul',
                'parent_name' => 'Carmen Garcia',
                'parent_email' => 'carmen.garcia@example.com',
                'parent_phone' => '+63 9123456794',
                'absences' => 3,
                'tardies' => 2
            ]
        ];

        foreach ($studentsData as $data) {
            // Get the correct adviser for this student's grade and section
            $adviserKey = $data['grade_level'] . '-' . $data['section'];
            $adviser = $advisers->get($adviserKey);
            
            if (!$adviser) {
                continue; // Skip if adviser not found
            }
            
            // Create or get student
            $student = Student::firstOrCreate(
                ['student_id' => $data['student_id']],
                [
                    'first_name' => $data['first_name'],
                    'middle_name' => $data['middle_name'],
                    'last_name' => $data['last_name'],
                    'date_of_birth' => now()->subYears(rand(13, 18))->toDateString(),
                    'gender' => rand(0, 1) ? 'male' : 'female',
                    'grade_level' => $data['grade_level'],
                    'section' => $data['section'],
                    'adviser_id' => $adviser->id,
                    'address' => rand(1, 5) . ' Sample Street, Metro Manila',
                    'status' => 'active'
                ]
            );

            // Create or get parent
            $nameParts = explode(' ', $data['parent_name']);
            $lastName = end($nameParts);
            $parent = ParentModel::firstOrCreate(
                ['email' => $data['parent_email']],
                [
                    'first_name' => $nameParts[0],
                    'last_name' => $lastName,
                    'phone' => $data['parent_phone'],
                    'address' => '123 Sample Street, Metro Manila',
                    'relationship' => 'parent',
                    'status' => 'active'
                ]
            );

            // Link student to parent if not already linked
            if (!$parent->students()->where('students.id', $student->id)->exists()) {
                $parent->students()->attach($student->id);
            }

            // Add attendance records for current semester
            $this->createAttendanceRecords($student, $data['absences'], $data['tardies']);

            // Add incidents
            $this->createIncidents($student);
        }
    }

    private function createAttendanceRecords($student, $absences, $tardies): void
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;
        
        if ($currentMonth >= 8) {
            $startMonth = 8;
            $endMonth = 12;
        } else {
            $startMonth = 1;
            $endMonth = 5;
        }

        // Create attendance records
        $schoolDays = 0;
        $absenceCount = 0;
        $tardyCount = 0;

        for ($month = $startMonth; $month <= $endMonth; $month++) {
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $currentYear);
            
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = \Carbon\Carbon::createFromDate($currentYear, $month, $day);
                
                // Skip weekends
                if ($date->isWeekend()) {
                    continue;
                }

                $schoolDays++;
                $status = 'present';

                if ($absenceCount < $absences && rand(1, 100) < 15) {
                    $status = 'absent';
                    $absenceCount++;
                } elseif ($tardyCount < $tardies && rand(1, 100) < 10) {
                    $status = 'tardy';
                    $tardyCount++;
                }

                AttendanceRecord::firstOrCreate(
                    [
                        'student_id' => $student->id,
                        'date' => $date->toDateString()
                    ],
                    [
                        'status' => $status,
                        'recorded_by' => 1  // Admin user
                    ]
                );
            }
        }
    }

    private function createIncidents($student): void
    {
        $categories = ViolationCategory::all();
        
        if ($categories->isEmpty()) {
            return;
        }

        // Randomly create 0-3 incidents per student
        $incidentCount = rand(0, 3);

        for ($i = 0; $i < $incidentCount; $i++) {
            $category = $categories->random();
            $statuses = ['reported', 'under_review', 'pending_approval', 'approved', 'closed'];
            $incidentNumber = 'INC-' . now()->format('Y') . '-' . str_pad($student->id . $i, 5, '0', STR_PAD_LEFT);
            
            $incident = Incident::create([
                'incident_number' => $incidentNumber,
                'incident_date' => now()->subDays(rand(1, 60)),
                'location' => 'Classroom',
                'violation_category_id' => $category->id,
                'description' => 'Behavioral incident: ' . $this->getIncidentDescription(),
                'reported_by' => User::where('email', 'discipline@spup.edu.ph')->first()->id ?? 1,
                'status' => $statuses[array_rand($statuses)]
            ]);

            // Link incident to student
            if ($incident) {
                $incident->students()->attach($student->id);
            }
        }
    }

    private function getIncidentDescription(): string
    {
        $descriptions = [
            'Student was late to class',
            'Incomplete homework submission',
            'Disruptive behavior in classroom',
            'Talking back to teacher',
            'Not following classroom rules',
            'Mobile phone usage during class',
            'Incomplete uniform',
            'Minor conflict with classmates',
            'Missing assignment',
            'Lack of participation in class activities'
        ];

        return $descriptions[array_rand($descriptions)];
    }
}
