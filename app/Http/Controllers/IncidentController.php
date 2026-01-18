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
    public function index(Request $request)
    {
        $query = Incident::with(['students', 'category', 'reporter.role']);

        // Filter by Grade Level
        if ($request->filled('grade_level')) {
            $query->whereHas('students', function ($q) use ($request) {
                $q->where('grade_level', $request->grade_level);
            });
        }

        // Filter by Section
        if ($request->filled('section')) {
            $query->whereHas('students', function ($q) use ($request) {
                $q->where('section', 'like', '%' . $request->section . '%');
            });
        }

        // Filter by Search (Student, Description, ID)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('incident_number', 'like', "%{$search}%")
                  ->orWhereHas('students', function ($subQ) use ($search) {
                      $subQ->where('first_name', 'like', "%{$search}%")
                           ->orWhere('last_name', 'like', "%{$search}%")
                           ->orWhere('student_id', 'like', "%{$search}%");
                  });
            });
        }

        $incidents = $query->orderBy('incident_date', 'desc')
            ->paginate(20)
            ->withQueryString();

        $violationCategories = ViolationCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $students = Student::where('status', 'active')
            ->orderBy('last_name')
            ->get();

        return view('incidents.index', compact('incidents', 'violationCategories', 'students'));
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
        \Illuminate\Support\Facades\Log::info('=== INCIDENT STORE DEBUG ===');
        \Illuminate\Support\Facades\Log::info('Raw students input:', ['students' => $request->input('students')]);
        \Illuminate\Support\Facades\Log::info('Raw non_student_names input:', ['non_student_names' => $request->input('non_student_names')]);

        $validated = $request->validate([
            'incident_date' => 'required|date',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'students' => 'nullable|array',
            'students.*' => 'exists:students,id',
            'non_student_names' => 'nullable|array',
            'non_student_names.*' => 'string|max:255',
            'violation_category_id' => 'nullable|exists:violation_categories,id',
            'violation_clause_id' => 'nullable|exists:violation_clauses,id',
            'narrative_reports' => 'nullable|array',
            'narrative_reports.*' => 'nullable|string',
            'narrative_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();
        try {
            // Process non-student participants
            $nonStudentString = null;
            if (!empty($validated['non_student_names'])) {
                $nonStudentString = implode(', ', $validated['non_student_names']);
            }

            // Create incident
            $incident = Incident::create([
                'incident_date' => $validated['incident_date'],
                'location' => $validated['location'],
                'description' => $validated['description'],
                'reported_by' => Auth::id(),
                'violation_category_id' => $validated['violation_category_id'] ?? null,
                'violation_clause_id' => $validated['violation_clause_id'] ?? null,
                'non_student_participant' => $nonStudentString,
                'status' => 'reported',
            ]);

            // Attach students if present
            \Illuminate\Support\Facades\Log::info('Validated students:', ['students' => $validated['students'] ?? 'NULL']);
            
            if (!empty($validated['students'])) {
                \Illuminate\Support\Facades\Log::info('Processing students. Count: ' . count($validated['students']));
                
                foreach ($validated['students'] as $index => $studentId) {
                    \Illuminate\Support\Facades\Log::info('Attaching student ID: ' . $studentId);
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
                    
                    \Illuminate\Support\Facades\Log::info('Successfully attached student ID: ' . $studentId);

                    // Send parent notification if required
                    if ($incident->category && $incident->category->requires_parent_notification) {
                        $this->sendParentNotification($incident, $student);
                    }
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

    public function summaryReport(Incident $incident)
    {
        $incident->load(['students', 'category', 'clause', 'reporter']);
        
        $clauses = $incident->category 
            ? $incident->category->clauses()->where('is_active', true)->get() 
            : collect();

        return view('incidents.summary-report', compact('incident', 'clauses'));
    }

    public function submitReport(Request $request, Incident $incident)
    {
        if ($incident->status !== 'under_review' && $incident->status !== 'reported') {
            return back()->with('error', 'Incident must be under review before submission.');
        }

        // Allow updating description/narration and action taken during submission
        if ($request->filled('description')) {
            $incident->description = $request->description;
        }

        if ($request->filled('action_taken')) {
            $incident->action_taken = $request->action_taken;
        }

        if ($request->filled('violation_clause_id')) {
            $incident->violation_clause_id = $request->violation_clause_id;
        }

        $incident->status = 'pending_approval';
        $incident->save();

        return redirect()->route('incidents.show', $incident)
            ->with('success', 'Incident report submitted for Principal approval.');
    }

    public function approve(Incident $incident)
    {
        $incident->update(['status' => 'approved']);
        return back()->with('success', 'Incident approved.');
    }

    public function submitForApproval(Incident $incident)
    {
        // Deprecated/Aliased to submitReport for backward compatibility if needed, 
        // but routes should use submitReport now.
        return $this->submitReport(request(), $incident);
    }

    public function toggleParentNotification(Incident $incident)
    {
        $incident->update([
            'is_parent_notified' => !$incident->is_parent_notified
        ]);
        
        $status = $incident->is_parent_notified ? 'marked as notified' : 'marked as not notified';
        
        return back()->with('success', "Parent notification status updated: $status");
    }

    public function toggleSanctionCompliance(Incident $incident, Student $student)
    {
        $record = $incident->students()->where('student_id', $student->id)->firstOrFail()->pivot;
        
        $newStatus = !$record->sanction_complied;
        
        $incident->students()->updateExistingPivot($student->id, [
            'sanction_complied' => $newStatus
        ]);

        $statusMsg = $newStatus ? 'marked as complied' : 'marked as pending';
        
        return back()->with('success', "Sanction status for {$student->full_name} updated: $statusMsg");
    }

    public function updateStudentSanction(Request $request, Incident $incident, Student $student)
    {
        $validated = $request->validate([
            'sanction_id' => 'required|exists:sanctions,id',
        ]);

        $incident->students()->updateExistingPivot($student->id, [
            'sanction_id' => $validated['sanction_id'],
            'sanction_complied' => false // Reset compliance when sanction changes
        ]);

        return back()->with('success', 'Sanction assigned to ' . $student->full_name);
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
