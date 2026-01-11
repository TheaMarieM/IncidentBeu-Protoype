<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\Student;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentPortalController extends Controller
{
    /**
     * Show the parent dashboard with children list
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Find the parent record by email
        $parent = ParentModel::where('email', $user->email)->first();

        if (!$parent) {
            abort(403, 'Parent/Guardian record not found. Please contact the school.');
        }

        // Get all children linked to this parent
        $children = $parent->students()->with('adviser')->get();

        return view('parent-portal.dashboard', compact('parent', 'children'));
    }

    /**
     * Show a specific child's attendance and incidents
     */
    public function viewChild(Student $student)
    {
        $user = Auth::user();
        
        // Find the parent record
        $parent = ParentModel::where('email', $user->email)->first();

        if (!$parent) {
            abort(403, 'Parent/Guardian record not found.');
        }

        // Check if this child belongs to this parent
        if (!$parent->students()->where('student_id', $student->student_id)->exists()) {
            abort(403, 'You do not have access to this student\'s information.');
        }

        // Get current semester/academic year
        $currentYear = now()->year;
        $currentMonth = now()->month;
        
        if ($currentMonth >= 8) {
            $semesterStart = now()->setMonth(8)->startOfMonth();
            $semesterEnd = now()->setMonth(12)->endOfMonth();
            $academicYear = $currentYear . '-' . ($currentYear + 1);
        } else {
            $semesterStart = now()->setMonth(1)->startOfMonth();
            $semesterEnd = now()->setMonth(5)->endOfMonth();
            $academicYear = ($currentYear - 1) . '-' . $currentYear;
        }

        // Calculate statistics
        $totalAbsences = AttendanceRecord::where('student_id', $student->id)
            ->where('status', 'absent')
            ->whereBetween('date', [$semesterStart, $semesterEnd])
            ->count();

        $totalTardiness = AttendanceRecord::where('student_id', $student->id)
            ->where('status', 'tardy')
            ->whereBetween('date', [$semesterStart, $semesterEnd])
            ->count();

        // Get recent incidents
        $incidents = $student->incidents()
            ->with(['category', 'reporter.role'])
            ->orderBy('incident_date', 'desc')
            ->limit(10)
            ->get();

        // Get recent attendance
        $attendanceRecords = AttendanceRecord::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        return view('parent-portal.view-child', compact(
            'parent',
            'student',
            'totalAbsences',
            'totalTardiness',
            'incidents',
            'attendanceRecords',
            'academicYear'
        ));
    }

    /**
     * Show parent profile
     */
    public function profile()
    {
        $user = Auth::user();
        
        $parent = ParentModel::where('email', $user->email)
            ->with(['students'])
            ->first();

        if (!$parent) {
            abort(403, 'Parent/Guardian record not found.');
        }

        return view('parent-portal.profile', compact('parent'));
    }
}
