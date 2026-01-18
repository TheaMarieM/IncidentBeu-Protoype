@extends('layouts.student')

@section('title', 'My Profile')

@section('content')
<!-- Header -->
<header class="bg-white border-b border-gray-200 px-8 py-5 sticky top-0 z-40">
    <div>
        <h2 class="text-xl font-bold text-gray-800">My Profile</h2>
        <p class="text-xs text-gray-500 font-medium mt-0.5">View your personal and academic information</p>
    </div>
</header>

<div class="p-8 max-w-5xl mx-auto">
    
    <!-- Profile Card -->
    <div class="bg-white rounded-xl border border-gray-200 card-shadow p-8 mb-6">
        <div class="flex items-start gap-6">
            <div class="w-24 h-24 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-3xl">
                {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h3 class="text-2xl font-bold text-gray-900">{{ $student->full_name }}</h3>
                <p class="text-sm text-gray-500 mt-1">Student ID: {{ $student->student_id }}</p>
                <div class="flex gap-4 mt-4">
                    <div class="bg-green-50 px-4 py-2 rounded-lg">
                        <p class="text-xs text-green-600 font-bold uppercase">Grade Level</p>
                        <p class="text-lg font-bold text-green-700">{{ $student->grade_level }}</p>
                    </div>
                    <div class="bg-green-50 px-4 py-2 rounded-lg">
                        <p class="text-xs text-green-600 font-bold uppercase">Section</p>
                        <p class="text-lg font-bold text-green-700">{{ $student->section }}</p>
                    </div>
                    <div class="bg-green-50 px-4 py-2 rounded-lg">
                        <p class="text-xs text-green-600 font-bold uppercase">Status</p>
                        <p class="text-lg font-bold text-green-700 capitalize">{{ $student->status }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Personal Information -->
        <div class="bg-white rounded-xl border border-gray-200 card-shadow p-6">
            <h4 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-user text-green-600"></i> Personal Information
            </h4>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold mb-1">Date of Birth</p>
                    <p class="text-gray-800 font-semibold">{{ $student->date_of_birth->format('F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold mb-1">Gender</p>
                    <p class="text-gray-800 font-semibold capitalize">{{ $student->gender }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold mb-1">Address</p>
                    <p class="text-gray-800">{{ $student->address ?? 'Not provided' }}</p>
                </div>
            </div>
        </div>

        <!-- Academic Information -->
        <div class="bg-white rounded-xl border border-gray-200 card-shadow p-6">
            <h4 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-graduation-cap text-green-600"></i> Academic Information
            </h4>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold mb-1">Adviser</p>
                    <p class="text-gray-800 font-semibold">{{ $student->adviser->name ?? 'Not assigned' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold mb-1">Enrollment Date</p>
                    <p class="text-gray-800 font-semibold">{{ $student->created_at->format('F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold mb-1">Student Type</p>
                    <p class="text-gray-800 font-semibold">Regular Student</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Parent/Guardian Information -->
    @if($student->parents->isNotEmpty())
    <div class="bg-white rounded-xl border border-gray-200 card-shadow p-6 mt-6">
        <h4 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-users text-green-600"></i> Parent/Guardian Information
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($student->parents as $parent)
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase font-bold mb-2">{{ $parent->relationship }}</p>
                <p class="text-base font-bold text-gray-800">{{ $parent->full_name }}</p>
                @if($parent->phone)
                <p class="text-sm text-gray-600 mt-2">
                    <i class="fa-solid fa-phone text-green-600"></i> {{ $parent->phone }}
                </p>
                @endif
                @if($parent->email)
                <p class="text-sm text-gray-600 mt-1">
                    <i class="fa-solid fa-envelope text-green-600"></i> {{ $parent->email }}
                </p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
