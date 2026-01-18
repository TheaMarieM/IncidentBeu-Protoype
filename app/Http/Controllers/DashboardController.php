<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Student;
use App\Models\InterventionSuggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get at-risk students (excessive absences or tardiness)
        $atRiskStudents = Student::where('status', 'active')
            ->withCount([
                'attendanceRecords as absent_count' => function ($query) {
                    $query->where('status', 'absent')
                        ->whereYear('date', now()->year);
                },
                'attendanceRecords as tardy_count' => function ($query) {
                    $query->where('status', 'tardy')
                        ->whereYear('date', now()->year);
                }
            ])
            ->having('absent_count', '>=', 10)
            ->orHaving('tardy_count', '>=', 15)
            ->get();

        // Get common incidents this quarter
        $quarterStart = now()->startOfQuarter();
        $quarterEnd = now()->endOfQuarter();

        $commonIncident = Incident::select('violation_category_id', DB::raw('count(*) as total'))
            ->whereBetween('incident_date', [$quarterStart, $quarterEnd])
            ->groupBy('violation_category_id')
            ->with('category')
            ->orderBy('total', 'desc')
            ->first();

        // Analyze which grade levels are most affected
        $affectedGradeLevels = [];
        if ($commonIncident) {
            $affectedGradeLevels = Incident::where('violation_category_id', $commonIncident->violation_category_id)
                ->whereBetween('incident_date', [$quarterStart, $quarterEnd])
                ->join('incident_students', 'incidents.id', '=', 'incident_students.incident_id')
                ->join('students', 'incident_students.student_id', '=', 'students.id')
                ->select('students.grade_level', 'students.section', DB::raw('count(*) as count'))
                ->groupBy('students.grade_level', 'students.section')
                ->orderBy('count', 'desc')
                ->take(3)
                ->get();
        }

        // Get pending approvals
        $pendingApprovals = Incident::where('status', 'pending_approval')
            ->with(['students', 'category', 'reporter'])
            ->count();

        // Get recent incidents with filters
        $query = Incident::with(['students', 'category', 'reporter']);

        // Apply filters
        if ($request->filled('grade_level')) {
            $query->whereHas('students', function($q) use ($request) {
                $q->where('grade_level', $request->grade_level);
            });
        }

        if ($request->filled('section')) {
            $query->whereHas('students', function($q) use ($request) {
                $q->where('section', 'like', '%' . $request->section . '%');
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('students', function($subQ) use ($search) {
                      $subQ->where('first_name', 'like', "%{$search}%")
                           ->orWhere('last_name', 'like', "%{$search}%")
                           ->orWhere('students.student_id', 'like', "%{$search}%");
                  })
                  ->orWhereHas('category', function($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $recentIncidents = $query->orderBy('incident_date', 'desc')
            ->take(20)
            ->get();

        // Get AI-driven intervention suggestions
        $interventionSuggestions = InterventionSuggestion::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Prepare data for view
        $atRiskStudentsCount = $atRiskStudents->count();
        $mostCommonIncident = $commonIncident?->category;
        $pendingApprovalsCount = $pendingApprovals;
        $suggestions = $interventionSuggestions;

        return view('dashboard.index', compact(
            'atRiskStudentsCount',
            'mostCommonIncident',
            'pendingApprovalsCount',
            'recentIncidents',
            'suggestions'
        ));
    }
}
