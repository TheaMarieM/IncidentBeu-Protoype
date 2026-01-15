<?php

namespace Database\Seeders;

use App\Models\Incident;
use App\Models\IncidentApproval;
use App\Models\Student;
use App\Models\User;
use App\Models\ViolationCategory;
use App\Models\ViolationClause;
use App\Models\Sanction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoApprovedIncidentSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get assistant principal user
        $assistantPrincipal = User::whereHas('role', function ($query) {
            $query->where('name', 'assistant_principal');
        })->first();

        // Get discipline chairperson for reporter
        $disciplineChair = User::whereHas('role', function ($query) {
            $query->where('name', 'discipline_chairperson');
        })->first();

        // Fallback: get any staff user if no discipline chair
        if (!$disciplineChair) {
            $disciplineChair = User::whereHas('role', function ($query) {
                $query->whereIn('name', ['teacher', 'adviser']);
            })->first();
        }

        // Get a student
        $student = Student::first();

        // Get violation data
        $category = ViolationCategory::first();
        $clause = ViolationClause::first();
        $sanction = Sanction::first();

        if (!$assistantPrincipal || !$student || !$category || !$clause || !$sanction || !$disciplineChair) {
            $this->command->warn('Missing required data. Please run other seeders first.');
            return;
        }

        // Create approved incident
        $incident = Incident::create([
            'incident_number' => 'INC-DEMO-' . now()->format('Ymd') . '-001',
            'incident_date' => now()->subDays(10),
            'description' => 'This is a demonstration incident approved by the Assistant Principal. The student was found engaging in unauthorized activities during class hours and received appropriate disciplinary action.',
            'location' => 'Main Building - Room 201',
            'reported_by' => $disciplineChair->id,
            'violation_category_id' => $category->id,
            'violation_clause_id' => $clause->id,
            'status' => 'approved',
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(5),
        ]);

        // Attach student with sanction
        $incident->students()->attach($student->id, [
            'narrative_report' => 'Student narrative: I acknowledge my mistake and understand the consequences.',
            'offense_count' => 1,
            'sanction_id' => $sanction->id,
        ]);

        // Create approval record by assistant principal
        IncidentApproval::create([
            'incident_id' => $incident->id,
            'approved_by' => $assistantPrincipal->id,
            'status' => 'approved',
            'remarks' => 'Case reviewed and approved. Sanction implemented as per student handbook.',
            'approved_at' => now()->subDays(5),
        ]);

        $this->command->info('Demo approved incident created successfully (approved by Assistant Principal).');
    }
}
