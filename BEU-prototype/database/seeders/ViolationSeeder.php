<?php

namespace Database\Seeders;

use App\Models\ViolationCategory;
use App\Models\ViolationClause;
use App\Models\Sanction;
use Illuminate\Database\Seeder;

class ViolationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bullying Category
        $bullying = ViolationCategory::create([
            'name' => 'Bullying (Verbal)',
            'description' => 'Verbal harassment, intimidation, or insults directed at other students',
            'severity' => 'major',
            'requires_parent_notification' => true,
            'sort_order' => 1,
        ]);

        $bullyingClause = ViolationClause::create([
            'violation_category_id' => $bullying->id,
            'clause_number' => 'Article IV, Section 3.1',
            'description' => 'Any form of verbal bullying including insults, threats, or intimidation',
        ]);

        Sanction::create([
            'violation_clause_id' => $bullyingClause->id,
            'offense_count' => 1,
            'sanction_description' => 'Written warning and mandatory counseling session with guidance office',
        ]);

        Sanction::create([
            'violation_clause_id' => $bullyingClause->id,
            'offense_count' => 2,
            'sanction_description' => 'Three-day suspension and mandatory parent conference',
        ]);

        Sanction::create([
            'violation_clause_id' => $bullyingClause->id,
            'offense_count' => 3,
            'sanction_description' => 'One-week suspension and recommendation for behavioral intervention program',
        ]);

        // Excessive Absences
        $absences = ViolationCategory::create([
            'name' => 'Excessive Absences',
            'description' => 'Unauthorized absences exceeding the allowed limit',
            'severity' => 'moderate',
            'requires_parent_notification' => true,
            'sort_order' => 2,
        ]);

        $absencesClause = ViolationClause::create([
            'violation_category_id' => $absences->id,
            'clause_number' => 'Article II, Section 1.3',
            'description' => 'Accumulation of unexcused absences beyond the allowed threshold',
        ]);

        Sanction::create([
            'violation_clause_id' => $absencesClause->id,
            'offense_count' => 1,
            'sanction_description' => 'Parent notification and academic warning',
        ]);

        Sanction::create([
            'violation_clause_id' => $absencesClause->id,
            'offense_count' => 2,
            'sanction_description' => 'Mandatory parent conference and monitoring period',
        ]);

        // Excessive Tardiness
        $tardiness = ViolationCategory::create([
            'name' => 'Excessive Tardiness',
            'description' => 'Repeated late arrivals to class or school',
            'severity' => 'minor',
            'requires_parent_notification' => false,
            'sort_order' => 3,
        ]);

        $tardinessClause = ViolationClause::create([
            'violation_category_id' => $tardiness->id,
            'clause_number' => 'Article II, Section 1.1',
            'description' => 'Late arrival to class or school beyond the grace period',
        ]);

        Sanction::create([
            'violation_clause_id' => $tardinessClause->id,
            'offense_count' => 1,
            'sanction_description' => 'Verbal warning and recording in attendance log',
        ]);

        Sanction::create([
            'violation_clause_id' => $tardinessClause->id,
            'offense_count' => 2,
            'sanction_description' => 'Written notice to parents and detention',
        ]);

        // Disrespect to Authority
        $disrespect = ViolationCategory::create([
            'name' => 'Disrespect to Authority',
            'description' => 'Disrespectful behavior towards teachers or staff',
            'severity' => 'major',
            'requires_parent_notification' => true,
            'sort_order' => 4,
        ]);

        $disrespectClause = ViolationClause::create([
            'violation_category_id' => $disrespect->id,
            'clause_number' => 'Article III, Section 2.1',
            'description' => 'Speaking disrespectfully or refusing to follow instructions from school personnel',
        ]);

        Sanction::create([
            'violation_clause_id' => $disrespectClause->id,
            'offense_count' => 1,
            'sanction_description' => 'Written apology and parent notification',
        ]);

        Sanction::create([
            'violation_clause_id' => $disrespectClause->id,
            'offense_count' => 2,
            'sanction_description' => 'Two-day suspension and mandatory parent conference',
        ]);

        // Cheating
        $cheating = ViolationCategory::create([
            'name' => 'Academic Dishonesty',
            'description' => 'Cheating, plagiarism, or any form of academic misconduct',
            'severity' => 'moderate',
            'requires_parent_notification' => true,
            'sort_order' => 5,
        ]);

        $cheatingClause = ViolationClause::create([
            'violation_category_id' => $cheating->id,
            'clause_number' => 'Article V, Section 1.2',
            'description' => 'Copying, plagiarism, or using unauthorized materials during assessments',
        ]);

        Sanction::create([
            'violation_clause_id' => $cheatingClause->id,
            'offense_count' => 1,
            'sanction_description' => 'Zero grade on the assessment and parent notification',
        ]);

        Sanction::create([
            'violation_clause_id' => $cheatingClause->id,
            'offense_count' => 2,
            'sanction_description' => 'Failed grade in the subject and mandatory ethics seminar',
        ]);
    }
}
