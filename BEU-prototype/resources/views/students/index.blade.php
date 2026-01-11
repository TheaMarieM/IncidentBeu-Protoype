@extends('layouts.app')

@section('content')
<!-- Header -->
<header class="bg-white border-b border-gray-200 px-8 py-5 flex justify-between items-center sticky top-0 z-40">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Student & Parent Registry</h2>
        <p class="text-xs text-gray-500 font-medium mt-0.5">Centralized database for the Basic Education Unit</p>
    </div>
    <div>
        <a href="{{ route('students.create') }}" class="bg-green-700 hover:bg-green-800 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-user-plus text-xs"></i> Register New Student
        </a>
    </div>
</header>

<div class="p-8 max-w-7xl mx-auto">
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Enrolled -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 card-shadow">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">Total Enrolled</p>
            <h4 class="text-4xl font-bold text-gray-900">{{ $totalEnrolled }}</h4>
        </div>

        <!-- Junior High Dept -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 card-shadow">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">Junior High Dept</p>
            <h4 class="text-4xl font-bold text-gray-900 mb-1">{{ $juniorHighDept }}</h4>
            <p class="text-xs text-gray-500">Sampled</p>
        </div>

        <!-- At-Risk (Absences) -->
        <div class="bg-white p-6 rounded-xl border-2 border-red-300 card-shadow">
            <p class="text-[11px] font-bold text-red-500 uppercase tracking-wider mb-3">At-Risk (Absences)</p>
            <h4 class="text-4xl font-bold text-gray-900">{{ str_pad($atRiskAbsences, 2, '0', STR_PAD_LEFT) }}</h4>
        </div>

        <!-- Active Interventions -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 card-shadow">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">Active Interventions</p>
            <h4 class="text-4xl font-bold text-amber-600">{{ str_pad($activeInterventions, 2, '0', STR_PAD_LEFT) }}</h4>
        </div>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-xl border border-gray-200 card-shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center gap-4">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                <input type="text" placeholder="Search by name, grade, or parent..." 
                       class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-xs focus:ring-1 focus:ring-green-500 outline-none w-full bg-gray-50">
            </div>
            <div class="flex gap-2">
                <select class="px-4 py-2 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 focus:ring-1 focus:ring-green-500 outline-none bg-white">
                    <option>All Grade Levels</option>
                    <option>Grade 7</option>
                    <option>Grade 8</option>
                    <option>Grade 9</option>
                    <option>Grade 10</option>
                    <option>Grade 11</option>
                    <option>Grade 12</option>
                </select>
                <button class="px-4 py-2 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-50 flex items-center gap-2">
                    <i class="fa-solid fa-sliders"></i>
                    Advanced Filter
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Student Profile</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Grade & Section</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Parent/Guardian</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Contact Sync</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Risk Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Records</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($students as $student)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-sm uppercase">
                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $student->full_name }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $student->student_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">Grade {{ $student->grade_level }}</div>
                            <div class="text-xs text-gray-500">Section {{ $student->section }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($student->parents->isNotEmpty())
                                @php
                                    $parent = $student->parents->first();
                                @endphp
                                <div class="font-semibold text-gray-800">{{ $parent->full_name }}</div>
                                <div class="text-xs text-gray-500 uppercase">{{ $parent->relationship }}</div>
                            @else
                                <span class="text-gray-400 italic text-xs">No guardian assigned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if($student->parents->isNotEmpty() && $student->parents->first()->email)
                                    <div class="w-6 h-6 rounded bg-green-100 flex items-center justify-center">
                                        <i class="fa-solid fa-envelope text-green-600 text-xs"></i>
                                    </div>
                                @endif
                                @if($student->parents->isNotEmpty() && $student->parents->first()->phone)
                                    <div class="w-6 h-6 rounded bg-green-100 flex items-center justify-center">
                                        <i class="fa-solid fa-message text-green-600 text-xs"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $absentCount = $student->attendanceRecords()
                                    ->where('status', 'absent')
                                    ->whereYear('date', now()->year)
                                    ->count();
                                $isHighRisk = $absentCount >= 10;
                            @endphp
                            @if($isHighRisk)
                                <span class="text-red-600 font-bold text-xs uppercase tracking-wide">High Risk</span>
                            @else
                                <span class="text-green-600 font-bold text-xs uppercase tracking-wide">Stable</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('students.show', $student) }}" class="text-green-600 hover:text-green-800 font-bold text-xs uppercase tracking-wide">
                                View Profile
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">
                            No students registered yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 flex justify-between items-center">
            <div class="text-xs text-gray-500">
                Showing {{ $students->firstItem() }}-{{ $students->lastItem() }} of {{ $students->total() }} students
            </div>
            {{ $students->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
