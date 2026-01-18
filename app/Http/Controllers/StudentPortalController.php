<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentPortalController extends Controller
{
    /**
     * Show the student dashboard (My Attendance)
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Find the student record linked to this user by employee_id (which is the student_id)
        $student = Student::where('student_id', $user->employee_id)->first();

        if (!$student) {
            abort(403, 'Student record not found. Please contact the administrator.');
        }

        // Get current semester/academic year
        $currentYear = now()->year;
        $currentMonth = now()->month;
        
        // Define semester based on month (adjust as needed)
        // Aug-Dec = First Semester, Jan-May = Second Semester
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

        // Get attendance history with pagination
        $attendanceHistory = AttendanceRecord::where('student_id', $student->id)
            ->with(['reporter'])
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('student-portal.dashboard', compact(
            'student',
            'totalAbsences',
            'totalTardiness',
            'attendanceHistory',
            'academicYear'
        ));
    }

    /**
     * Show student profile
     */
    public function profile()
    {
        $user = Auth::user();
        
        $student = Student::where('student_id', $user->employee_id)
            ->with(['adviser', 'parents'])
            ->first();

        if (!$student) {
            abort(403, 'Student record not found.');
        }

        return view('student-portal.profile', compact('student'));
    }

    /**
     * Show student incidents
     */
    public function incidents()
    {
        $user = Auth::user();
        
        $student = Student::where('student_id', $user->employee_id)->first();

        if (!$student) {
            abort(403, 'Student record not found.');
        }

        $incidents = $student->incidents()
            ->with(['category', 'reporter.role'])
            ->orderBy('incident_date', 'desc')
            ->paginate(10);

        return view('student-portal.incidents', compact('student', 'incidents'));
    }
}
