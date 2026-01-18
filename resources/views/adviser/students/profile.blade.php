@extends('layouts.adviser')

@section('content')
@php
    $userName = auth()->user()->name ?? 'Adviser';
    $sectionName = auth()->user()->section ?? 'Adviser';
    $adviserTitle = $sectionName ? $sectionName . "'s Adviser" : 'Adviser';
    $fullName = $student->first_name . ' ' . ($student->middle_name ? $student->middle_name . ' ' : '') . $student->last_name;
@endphp

<header class="bg-white border-b border-gray-200 px-8 py-4 flex flex-wrap gap-4 justify-between items-center sticky top-0 z-30 shadow-sm">
    <div>
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('adviser.students.show', $student) }}" class="text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Student Profile</h1>
        </div>
        <p class="text-sm text-gray-500 mt-1">{{ $fullName }} · {{ $student->student_id }}</p>
    </div>
    <div class="flex items-center gap-4">
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
    <section class="max-w-5xl mx-auto space-y-6">
        <!-- Basic Information -->
        <div class="bg-white border border-gray-200 rounded-3xl overflow-hidden card-shadow">
            <div class="px-7 py-6 border-b border-gray-100">
                <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold">Student Information</p>
                <h2 class="text-2xl font-black text-gray-900 mt-1">Basic Details</h2>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold mb-2">Student ID</p>
                    <p class="text-base font-semibold text-gray-900">{{ $student->student_id }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold mb-2">Full Name</p>
                    <p class="text-base font-semibold text-gray-900">{{ $fullName }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold mb-2">Email Address</p>
                    <p class="text-base font-semibold text-gray-900">{{ $student->email ?? 'Not provided' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold mb-2">Status</p>
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold {{ $student->status === 'active' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($student->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="bg-white border border-gray-200 rounded-3xl overflow-hidden card-shadow">
            <div class="px-7 py-6 border-b border-gray-100">
                <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold">Address Information</p>
                <h2 class="text-2xl font-black text-gray-900 mt-1">Residential Details</h2>
            </div>
            <div class="p-8 space-y-6">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold mb-2">Residential Address</p>
                    <p class="text-base text-gray-900">{{ $student->residential_address ?? 'Not provided' }}</p>
                </div>
                @if($student->boarding_address)
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold mb-2">Apartment / Boarding House Address</p>
                    <p class="text-base text-gray-900">{{ $student->boarding_address }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Guardian Information -->
        <div class="bg-white border border-gray-200 rounded-3xl overflow-hidden card-shadow">
            <div class="px-7 py-6 border-b border-gray-100">
                <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold">Primary Guardian</p>
                <h2 class="text-2xl font-black text-gray-900 mt-1">Emergency Contact</h2>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold mb-2">Guardian Name</p>
                    <p class="text-base font-semibold text-gray-900">{{ $student->guardian_name ?? 'Not provided' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold mb-2">Contact Number</p>
                    <p class="text-base font-semibold text-gray-900">{{ $student->guardian_contact ?? 'Not provided' }}</p>
                </div>
            </div>
        </div>

        <!-- Family Information -->
        <div class="bg-white border border-gray-200 rounded-3xl overflow-hidden card-shadow">
            <div class="px-7 py-6 border-b border-gray-100">
                <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold">Family Information</p>
                <h2 class="text-2xl font-black text-gray-900 mt-1">Parents Details</h2>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold mb-2">Mother's Full Name</p>
                    <p class="text-base font-semibold text-gray-900">{{ $student->mother_name ?? 'Not provided' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold mb-2">Father's Full Name</p>
                    <p class="text-base font-semibold text-gray-900">{{ $student->father_name ?? 'Not provided' }}</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-center gap-4 pt-4">
            <a href="{{ route('adviser.students.show', $student) }}" class="px-6 py-3 text-sm font-semibold rounded-lg bg-gray-200 text-gray-800 hover:bg-gray-300 transition-colors inline-flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Records
            </a>
            <a href="{{ route('adviser.students.edit', $student) }}" class="px-6 py-3 text-sm font-semibold rounded-lg bg-green-700 text-white hover:bg-green-800 transition-colors inline-flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square"></i> Edit Profile
            </a>
        </div>
    </section>
</div>

@endsection
