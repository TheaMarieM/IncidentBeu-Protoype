<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdviserController extends Controller
{
    public function index()
    {
        $advisers = User::whereHas('role', function ($query) {
            $query->where('name', 'adviser');
        })
        ->with(['role', 'advisedStudents'])
        ->orderBy('name')
        ->paginate(20);

        // Calculate statistics
        $totalAdvisers = User::whereHas('role', function ($query) {
            $query->where('name', 'adviser');
        })->where('status', 'active')->count();

        $adviserRole = \App\Models\Role::where('name', 'adviser')->first();
        
        $sectionsCovered = User::where('role_id', $adviserRole->id ?? 0)
            ->whereHas('advisedStudents')
            ->distinct('id')
            ->count();

        $reportsLogged = \App\Models\Incident::whereHas('reporter.role', function ($query) {
            $query->where('name', 'adviser');
        })->count();

        return view('advisers.index', compact('advisers', 'totalAdvisers', 'sectionsCovered', 'reportsLogged'));
    }

    public function create()
    {
        return view('advisers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'employee_id' => 'required|string|unique:users,employee_id|max:255',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
        ]);

        $adviserRole = \App\Models\Role::where('name', 'adviser')->first();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'employee_id' => $validated['employee_id'],
            'password' => Hash::make($validated['password']),
            'role_id' => $adviserRole->id,
            'phone' => $validated['phone'] ?? null,
            'department' => $validated['department'] ?? null,
        ]);

        return redirect()->route('advisers.index')
            ->with('success', 'Adviser registered successfully.');
    }

    public function show(User $adviser)
    {
        $adviser->load(['role', 'advisedStudents', 'reportedIncidents']);

        return view('advisers.show', compact('adviser'));
    }

    public function edit(User $adviser)
    {
        return view('advisers.edit', compact('adviser'));
    }

    public function update(Request $request, User $adviser)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $adviser->id,
            'employee_id' => 'required|string|max:255|unique:users,employee_id,' . $adviser->id,
            'phone' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'employee_id' => $validated['employee_id'],
            'phone' => $validated['phone'] ?? null,
            'department' => $validated['department'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $adviser->update($updateData);

        return redirect()->route('advisers.show', $adviser)
            ->with('success', 'Adviser updated successfully.');
    }

    public function destroy(User $adviser)
    {
        $adviser->delete();

        return redirect()->route('advisers.index')
            ->with('success', 'Adviser deleted successfully.');
    }
}
