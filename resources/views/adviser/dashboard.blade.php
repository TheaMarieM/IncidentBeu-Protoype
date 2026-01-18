@extends('layouts.adviser')

@section('content')
@php
    $userName = auth()->user()->name ?? 'Adviser';
    $sectionName = auth()->user()->section ?? 'Adviser';
    $adviserTitle = $sectionName ? $sectionName . "'s Adviser" : 'Adviser';
@endphp

<header class="bg-white border-b border-gray-200 px-8 py-4 flex flex-wrap gap-4 justify-between items-center sticky top-0 z-30 shadow-sm">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Adviser's Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Welcome back, {{ $userName }}</p>
    </div>
    <div class="flex items-center gap-4">
        <a href="{{ route('adviser.students.create') }}" class="px-6 py-3 text-sm font-semibold rounded-lg bg-green-700 text-white shadow hover:bg-green-800 transition-colors inline-flex items-center gap-2">
            <i class="fa-solid fa-user-plus"></i> Register Student
        </a>
        <div class="h-10 w-px bg-gray-300"></div>
        <div class="flex items-center gap-3">
            <p class="text-sm font-bold text-gray-900">{{ $adviserTitle }}</p>
            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                <i class="fa-solid fa-user"></i>
            </div>
        </div>
    </div>
</header>

<div class="p-8 space-y-8">
    <section class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <div class="bg-white border border-gray-200 rounded-3xl p-6 card-shadow">
            <div class="flex items-center justify-between">
                <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold">Total Advisees</p>
                <span class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-users"></i>
                </span>
            </div>
            <h3 class="text-4xl font-black text-gray-900 mt-3">{{ str_pad($totalAdvisees, 2, '0', STR_PAD_LEFT) }}</h3>
            <p class="text-xs text-gray-500 mt-2">Students under your advisory</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-3xl p-6 card-shadow">
            <div class="flex items-center justify-between">
                <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold">Total Incidents</p>
                <span class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center">
                    <i class="fa-solid fa-clipboard-list"></i>
                </span>
            </div>
            <h3 class="text-4xl font-black text-rose-600 mt-3">{{ str_pad($totalIncidents, 2, '0', STR_PAD_LEFT) }}</h3>
            <p class="text-xs text-gray-500 mt-2">Class total incidents</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-3xl p-6 card-shadow">
            <div class="flex items-center justify-between">
                <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold">Total Tardy</p>
                <span class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-clock"></i>
                </span>
            </div>
            <h3 class="text-4xl font-black text-amber-600 mt-3">{{ str_pad($totalTardy, 2, '0', STR_PAD_LEFT) }}</h3>
            <p class="text-xs text-gray-500 mt-2">Class total tardy records</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-3xl p-6 card-shadow">
            <div class="flex items-center justify-between">
                <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold">Total Absences</p>
                <span class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center">
                    <i class="fa-solid fa-calendar-xmark"></i>
                </span>
            </div>
            <h3 class="text-4xl font-black text-purple-600 mt-3">{{ str_pad($totalAbsent, 2, '0', STR_PAD_LEFT) }}</h3>
            <p class="text-xs text-gray-500 mt-2">Class total absences</p>
        </div>
    </section>

    <section class="bg-white border border-gray-200 rounded-3xl overflow-hidden card-shadow">
        <div class="px-7 py-6 border-b border-gray-100 flex flex-wrap gap-3 justify-between items-center">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold">Advisees List</p>
                <h2 class="text-2xl font-black text-gray-900 mt-1">Student Records Summary</h2>
                <p class="text-xs text-gray-500 mt-1">Click any student to view detailed incident records.</p>
            </div>
            <div class="flex gap-2">
                <button class="px-4 py-2 text-xs font-semibold border border-gray-200 rounded-full text-gray-600 hover:bg-gray-50">
                    <i class="fa-solid fa-magnifying-glass mr-2"></i> Search Student
                </button>
                <button class="px-4 py-2 text-xs font-semibold border border-gray-200 rounded-full text-gray-600 hover:bg-gray-50">
                    <i class="fa-solid fa-filter mr-2"></i> Filters
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-[10px] uppercase tracking-[0.2em] text-gray-500">
                    <tr>
                        <th class="px-6 py-4 text-left">Student ID</th>
                        <th class="px-6 py-4 text-left">Full Name</th>
                        <th class="px-6 py-4 text-left">Incidents</th>
                        <th class="px-6 py-4 text-left">Tardy</th>
                        <th class="px-6 py-4 text-left">Absent</th>
                        <th class="px-6 py-4 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($advisees as $student)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-gray-600 font-medium">{{ $student->student_id }}</td>
                        <td class="px-6 py-4 text-gray-900 font-semibold">{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold {{ $student->incidents_count > 0 ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-gray-100 text-gray-600' }}">
                                {{ $student->incidents_count }} {{ $student->incidents_count === 1 ? 'incident' : 'incidents' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold {{ $student->tardy_count > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-gray-100 text-gray-600' }}">
                                {{ $student->tardy_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold {{ $student->absent_count > 0 ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-gray-100 text-gray-600' }}">
                                {{ $student->absent_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('adviser.students.show', $student) }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold rounded-lg bg-green-700 text-white hover:bg-green-800 transition-colors">
                                <i class="fa-solid fa-eye"></i> View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fa-solid fa-inbox text-4xl mb-3 opacity-30"></i>
                            <p class="font-semibold">No advisees found</p>
                            <p class="text-xs mt-1">Register students to see them listed here.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

@endsection
