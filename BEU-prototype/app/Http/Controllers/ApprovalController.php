<?php

namespace App\Http\Controllers;

use App\Models\IncidentApproval;
use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function index()
    {
        $pendingIncidents = Incident::where('status', 'pending_approval')
            ->with(['students', 'category', 'clause', 'reporter'])
            ->orderBy('incident_date', 'desc')
            ->paginate(20);

        $approvedIncidents = Incident::where('status', 'approved')
            ->with(['students', 'category', 'approvals.approver'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('approvals.index', compact('pendingIncidents', 'approvedIncidents'));
    }

    public function approve(Request $request, Incident $incident)
    {
        if ($incident->status !== 'pending_approval') {
            return back()->with('error', 'This incident is not pending approval.');
        }

        $validated = $request->validate([
            'remarks' => 'nullable|string',
        ]);

        IncidentApproval::create([
            'incident_id' => $incident->id,
            'approved_by' => Auth::id(),
            'status' => 'approved',
            'remarks' => $validated['remarks'] ?? null,
            'approved_at' => now(),
        ]);

        $incident->update(['status' => 'approved']);

        return redirect()->route('approvals.index')
            ->with('success', 'Incident approved successfully. Ready to print.');
    }

    public function reject(Request $request, Incident $incident)
    {
        if ($incident->status !== 'pending_approval') {
            return back()->with('error', 'This incident is not pending approval.');
        }

        $validated = $request->validate([
            'remarks' => 'required|string',
        ]);

        IncidentApproval::create([
            'incident_id' => $incident->id,
            'approved_by' => Auth::id(),
            'status' => 'rejected',
            'remarks' => $validated['remarks'],
            'approved_at' => now(),
        ]);

        $incident->update(['status' => 'under_review']);

        return redirect()->route('approvals.index')
            ->with('success', 'Incident rejected. Returned for revision.');
    }
}
