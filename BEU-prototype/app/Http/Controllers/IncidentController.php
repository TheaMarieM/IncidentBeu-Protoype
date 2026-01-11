<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\Incident;
use App\Models\Student;
use App\Models\ViolationCategory;
use App\Models\ViolationClause;
use App\Models\Sanction;
use App\Models\ParentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IncidentController extends Controller
{
    public function index()
    {
        $incidents = Incident::with(['students', 'category', 'reporter.role'])
            ->orderBy('incident_date', 'desc')
            ->paginate(20);

        $violationCategories = ViolationCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('incidents.index', compact('incidents', 'violationCategories'));
    }

    public function create()
    {
        $students = Student::where('status', 'active')
            ->orderBy('last_name')
            ->get();
        
        $categories = ViolationCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('incidents.create', compact('students', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'incident_date' => 'required|date',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'students' => 'required|array|min:1',
            'students.*' => 'exists:students,id',
            'violation_category_id' => 'nullable|exists:violation_categories,id',
            'violation_clause_id' => 'nullable|exists:violation_clauses,id',
            'narrative_reports' => 'nullable|array',
            'narrative_reports.*' => 'nullable|string',
            'narrative_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();
        try {
            // Create incident
            $incident = Incident::create([
                'incident_date' => $validated['incident_date'],
                'location' => $validated['location'],
                'description' => $validated['description'],
                'reported_by' => Auth::id(),
                'violation_category_id' => $validated['violation_category_id'] ?? null,
                'violation_clause_id' => $validated['violation_clause_id'] ?? null,
                'status' => 'reported',
            ]);

            // Attach students
            foreach ($validated['students'] as $index => $studentId) {
                $student = Student::find($studentId);
                $offenseCount = $this->calculateOffenseCount($studentId, $validated['violation_category_id']);
                
                $narrativeFilePath = null;
                if ($request->hasFile("narrative_files.{$index}")) {
                    $file = $request->file("narrative_files.{$index}");
                    $narrativeFilePath = $file->store('narrative_reports', 'private');
                }

                $incident->students()->attach($studentId, [
                    'narrative_report' => $validated['narrative_reports'][$index] ?? null,
                    'narrative_file_path' => $narrativeFilePath,
                    'offense_count' => $offenseCount,
                ]);

                // Send parent notification if required
                if ($incident->category && $incident->category->requires_parent_notification) {
                    $this->sendParentNotification($incident, $student);
                }
            }

            DB::commit();

            return redirect()->route('incidents.show', $incident)
                ->with('success', 'Incident logged successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to log incident: ' . $e->getMessage());
        }
    }

    public function show(Incident $incident)
    {
        $incident->load(['students.parents', 'category', 'clause', 'reporter', 'notifications', 'approvals.approver']);

        // Get available clauses if category is selected
        $clauses = $incident->category 
            ? $incident->category->clauses()->where('is_active', true)->get() 
            : collect();

        // Get available sanctions for each student
        $studentsWithSanctions = $incident->students->map(function ($student) use ($incident) {
            $offenseCount = $student->pivot->offense_count;
            $sanctions = $incident->clause 
                ? $incident->clause->sanctions()
                    ->where('offense_count', $offenseCount)
                    ->where('is_active', true)
                    ->get()
                : collect();
            
            $student->available_sanctions = $sanctions;
            return $student;
        });

        return view('incidents.show', compact('incident', 'clauses', 'studentsWithSanctions'));
    }

    public function updateViolation(Request $request, Incident $incident)
    {
        $validated = $request->validate([
            'violation_category_id' => 'required|exists:violation_categories,id',
            'violation_clause_id' => 'required|exists:violation_clauses,id',
            'sanctions' => 'required|array',
            'sanctions.*' => 'required|exists:sanctions,id',
        ]);

        DB::beginTransaction();
        try {
            $incident->update([
                'violation_category_id' => $validated['violation_category_id'],
                'violation_clause_id' => $validated['violation_clause_id'],
                'status' => 'under_review',
            ]);

            // Update sanctions for each student
            foreach ($validated['sanctions'] as $studentId => $sanctionId) {
                $incident->students()->updateExistingPivot($studentId, [
                    'sanction_id' => $sanctionId,
                ]);
            }

            DB::commit();

            return redirect()->route('incidents.show', $incident)
                ->with('success', 'Violation details updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update violation: ' . $e->getMessage());
        }
    }

    public function submitForApproval(Incident $incident)
    {
        if ($incident->status !== 'under_review') {
            return back()->with('error', 'Incident must be under review before submission.');
        }

        $incident->update(['status' => 'pending_approval']);

        return redirect()->route('incidents.show', $incident)
            ->with('success', 'Incident submitted for Principal approval.');
    }

    private function calculateOffenseCount($studentId, $categoryId)
    {
        if (!$categoryId) {
            return 1;
        }

        return Incident::where('violation_category_id', $categoryId)
            ->whereHas('students', function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })
            ->count() + 1;
    }

    private function sendParentNotification(Incident $incident, Student $student)
    {
        $parents = $student->parents;

        foreach ($parents as $parent) {
            $message = "Dear {$parent->full_name}, \n\n";
            $message .= "This is to inform you that {$student->full_name} has been involved in a behavioral incident at St. Paul University Philippines - BEU. ";
            $message .= "Please visit the school to discuss this matter with the Discipline Office at your earliest convenience.\n\n";
            $message .= "Thank you for your cooperation.\n";
            $message .= "BEU Discipline Office";

            ParentNotification::create([
                'incident_id' => $incident->id,
                'parent_id' => $parent->id,
                'student_id' => $student->id,
                'notification_type' => $parent->email ? 'email' : 'sms',
                'message' => $message,
                'status' => 'pending',
            ]);
        }
    }

    public function searchStudents(Request $request)
    {
        $search = $request->input('q', '');
        
        $students = Student::where('status', 'active')
            ->where(function($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('student_id', 'like', "%{$search}%");
            })
            ->select('id', 'student_id', 'first_name', 'last_name', 'grade_level', 'section')
            ->orderBy('last_name')
            ->limit(10)
            ->get();

        return response()->json($students->map(function($student) {
            return [
                'id' => $student->id,
                'text' => "{$student->last_name}, {$student->first_name} ({$student->student_id})",
                'student_id' => $student->student_id,
                'grade_level' => $student->grade_level,
                'section' => $student->section
            ];
        }));
    }
}
