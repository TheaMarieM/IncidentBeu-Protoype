<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\Student;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function index()
    {
        $parents = ParentModel::withCount('students')
            ->orderBy('last_name')
            ->paginate(20);

        // Calculate statistics
        $registeredParents = ParentModel::count();
        
        $notificationsSentToday = \App\Models\ParentNotification::whereDate('created_at', today())->count();
        
        $syncStatus = 100; // Placeholder for actual sync status calculation

        return view('parents.index', compact('parents', 'registeredParents', 'notificationsSentToday', 'syncStatus'));
    }

    public function create()
    {
        $students = Student::where('status', 'active')
            ->orderBy('last_name')
            ->get();

        return view('parents.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:255',
            'alternate_phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'students' => 'nullable|array',
            'students.*' => 'exists:students,id',
            'primary_contact' => 'nullable|array',
        ]);

        $parent = ParentModel::create($validated);

        // Attach students
        if (!empty($validated['students'])) {
            foreach ($validated['students'] as $studentId) {
                $isPrimary = in_array($studentId, $validated['primary_contact'] ?? []);
                $parent->students()->attach($studentId, ['is_primary_contact' => $isPrimary]);
            }
        }

        return redirect()->route('parents.show', $parent)
            ->with('success', 'Parent registered successfully.');
    }

    public function show(ParentModel $parent)
    {
        $parent->load('students.adviser');

        return view('parents.show', compact('parent'));
    }

    public function edit(ParentModel $parent)
    {
        $students = Student::where('status', 'active')
            ->orderBy('last_name')
            ->get();

        $parent->load('students');

        return view('parents.edit', compact('parent', 'students'));
    }

    public function update(Request $request, ParentModel $parent)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:255',
            'alternate_phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'students' => 'nullable|array',
            'students.*' => 'exists:students,id',
            'primary_contact' => 'nullable|array',
        ]);

        $parent->update($validated);

        // Sync students
        if (isset($validated['students'])) {
            $syncData = [];
            foreach ($validated['students'] as $studentId) {
                $isPrimary = in_array($studentId, $validated['primary_contact'] ?? []);
                $syncData[$studentId] = ['is_primary_contact' => $isPrimary];
            }
            $parent->students()->sync($syncData);
        }

        return redirect()->route('parents.show', $parent)
            ->with('success', 'Parent updated successfully.');
    }
}
