<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ParentModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $user = Auth::user();
        $students = Student::with(['adviser', 'parents'])
            ->when(
                $user && $user->role && $user->role->name === 'adviser',
                function ($query) use ($user) {
                    return $query->where('adviser_id', $user->id);
                }
            )
            ->orderBy('last_name')
            ->paginate(20);

        // Calculate statistics
        $totalEnrolled = Student::where('status', 'active')->count();
        
        $juniorHighDept = Student::where('status', 'active')
            ->where('grade_level', 'LIKE', '%')
            ->count();
        
        $atRiskAbsences = Student::where('status', 'active')
            ->withCount([
                'attendanceRecords as absent_count' => function ($query) {
                    $query->where('status', 'absent')
                        ->whereYear('date', now()->year);
                }
            ])
            ->having('absent_count', '>=', 10)
            ->count();
        
        $activeInterventions = \App\Models\InterventionSuggestion::where('status', 'active')->count();

        return view('students.index', compact('students', 'totalEnrolled', 'juniorHighDept', 'atRiskAbsences', 'activeInterventions'));
    }

    public function create()
    {
        $advisers = User::whereHas('role', function ($query) {
            $query->where('name', 'adviser');
        })->orderBy('name')->get();

        return view('students.create', compact('advisers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string|unique:students,student_id|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'grade_level' => 'required|string|max:255',
            'section' => 'required|string|max:255',
            'adviser_id' => 'required|exists:users,id',
            'address' => 'nullable|string',
        ]);

        $student = Student::create($validated);

        return redirect()->route('students.show', $student)
            ->with('success', 'Student registered successfully.');
    }

    public function show(Student $student)
    {
        $student->load(['adviser', 'parents', 'incidents.category', 'attendanceRecords']);

        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $advisers = User::whereHas('role', function ($query) {
            $query->where('name', 'adviser');
        })->orderBy('name')->get();

        return view('students.edit', compact('student', 'advisers'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'student_id' => 'required|string|max:255|unique:students,student_id,' . $student->id,
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'grade_level' => 'required|string|max:255',
            'section' => 'required|string|max:255',
            'adviser_id' => 'required|exists:users,id',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive,dropped',
        ]);

        $student->update($validated);

        return redirect()->route('students.show', $student)
            ->with('success', 'Student updated successfully.');
    }
}
