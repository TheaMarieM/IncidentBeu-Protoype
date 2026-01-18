@extends('layouts.app')

@section('content')
<!-- Header -->
<header class="bg-white border-b border-gray-200 px-8 py-5 sticky top-0 z-40 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Student Profile</h2>
        <p class="text-xs text-gray-500 font-medium mt-0.5">Comprehensive academic and behavioral record</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('students.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-50 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to List
        </a>
        <a href="{{ route('students.edit', $student) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-bold hover:bg-green-700 transition-colors">
            <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Profile
        </a>
    </div>
</header>

<div class="p-8 max-w-7xl mx-auto">
    
    <!-- Profile Card -->
    <div class="bg-white rounded-xl border border-gray-200 card-shadow overflow-hidden mb-8">
        <div class="bg-green-900 h-24 relative">
             <div class="absolute -bottom-10 left-8">
                <div class="w-24 h-24 rounded-full border-4 border-white bg-gray-100 flex items-center justify-center text-3xl font-bold text-gray-600 shadow-md">
                    {{ substr($student->first_name, 0, 1) }}
                </div>
            </div>
        </div>
        <div class="pt-12 pb-6 px-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</h1>
                    <p class="text-sm text-gray-500 font-medium mb-4">ID: {{ $student->student_id }} <span class="mx-2">•</span> Grade {{ $student->grade_level }} - {{ $student->section }}</p>
                    
                    <div class="flex gap-6 mt-4">
                        <div class="text-xs">
                            <span class="block text-gray-400 font-bold uppercase tracking-wider mb-1">Status</span>
                            <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded font-bold uppercase">{{ $student->status }}</span>
                        </div>
                        <div class="text-xs">
                            <span class="block text-gray-400 font-bold uppercase tracking-wider mb-1">Gender</span>
                            <span class="text-gray-700 font-semibold capitalize">{{ $student->gender }}</span>
                        </div>
                        <div class="text-xs">
                            <span class="block text-gray-400 font-bold uppercase tracking-wider mb-1">Date of Birth</span>
                            <span class="text-gray-700 font-semibold">{{ $student->date_of_birth ? $student->date_of_birth->format('M d, Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 w-72">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Class Adviser</p>
                    @if($student->adviser)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-xs">
                                {{ substr($student->adviser->first_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $student->adviser->name }}</p>
                                <p class="text-xs text-blue-600">{{ $student->adviser->email }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-400 italic">No adviser assigned</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Contact & Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Parents / Guardians -->
            <div class="bg-white rounded-xl border border-gray-200 card-shadow p-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-users text-gray-400"></i> Guardians
                </h3>
                @forelse($student->parents as $parent)
                    <div class="mb-4 pb-4 border-b border-gray-100 last:border-0 last:pb-0 last:mb-0">
                        <p class="font-bold text-sm text-gray-800">{{ $parent->first_name }} {{ $parent->last_name }}</p>
                        <p class="text-xs text-gray-500 italic mb-2">{{ $parent->relationship }}</p>
                        <div class="space-y-1">
                            @if($parent->email)
                                <div class="flex items-center gap-2 text-xs text-gray-600">
                                    <i class="fa-solid fa-envelope w-4 text-center text-gray-400"></i> {{ $parent->email }}
                                </div>
                            @endif
                            @if($parent->phone)
                                <div class="flex items-center gap-2 text-xs text-gray-600">
                                    <i class="fa-solid fa-phone w-4 text-center text-gray-400"></i> {{ $parent->phone }}
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 italic">No guardians linked.</p>
                @endforelse
            </div>

            <!-- Address -->
            <div class="bg-white rounded-xl border border-gray-200 card-shadow p-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-location-dot text-gray-400"></i> Address
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $student->address ?? 'No address recorded.' }}</p>
            </div>
        </div>

        <!-- Right Column: Incidents & Stats -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Statistics Row -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white p-5 rounded-xl border border-gray-200 card-shadow">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Total Incidents</p>
                    <h2 class="text-3xl font-bold text-gray-900">{{ $student->incidents->count() }}</h2>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-200 card-shadow">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Absences (Year)</p>
                    @php
                        $absences = $student->attendanceRecords->where('status', 'absent')->count();
                    @endphp
                    <h2 class="text-3xl font-bold {{ $absences >= 10 ? 'text-red-600' : 'text-gray-900' }}">{{ $absences }}</h2>
                    @if($absences >= 10)
                        <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded mt-1 inline-block">At Risk</span>
                    @endif
                </div>
            </div>

            <!-- Behavioral Incidents List -->
            <div class="bg-white rounded-xl border border-gray-200 card-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Behavioral Incidents</h3>
                    <span class="text-xs text-gray-500">{{ $student->incidents->count() }} Record(s)</span>
                </div>
                
                <div class="divide-y divide-gray-100">
                    @forelse($student->incidents->sortByDesc('incident_date') as $incident)
                        <div class="p-6 hover:bg-gray-50 transition-colors group">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-bold text-gray-800 text-sm group-hover:text-green-700 transition">{{ $incident->category->name ?? 'General Incident' }}</h4>
                                    <p class="text-xs text-gray-500">{{ $incident->incident_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($incident->incident_time)->format('h:i A') }}</p>
                                </div>
                                <span class="px-2.5 py-1 rounded text-[10px] font-bold uppercase border 
                                    {{ $incident->status === 'approved' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-gray-50 text-gray-600 border-gray-100' }}">
                                    {{ str_replace('_', ' ', $incident->status) }}
                                </span>
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-4">{{ $incident->description }}</p>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-400">
                                    <i class="fa-solid fa-location-dot mr-1"></i> {{ $incident->location }}
                                </span>
                                <a href="{{ route('incidents.show', $incident) }}" class="text-xs font-bold text-green-600 hover:text-green-800 flex items-center gap-1">
                                    View Full Case <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-check text-green-500 text-xl"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-900">Clean Record</p>
                            <p class="text-xs text-gray-500 mt-1">No behavioral incidents reported.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
