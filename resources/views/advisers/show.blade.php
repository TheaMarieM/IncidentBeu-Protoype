@extends('layouts.app')

@section('content')
<div class="p-8 max-w-7xl mx-auto space-y-8">
    <!-- Header with Breadcrumb -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('advisers.index') }}" class="hover:text-green-700 transition-colors">Advisers</a>
                <i class="fa-solid fa-chevron-right text-xs"></i>
                <span class="text-gray-800 font-medium">Profile Details</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-3">
                <span class="w-10 h-10 rounded-full bg-slate-800 text-white flex items-center justify-center text-lg font-bold">
                    {{ substr($adviser->name, 0, 1) }}
                </span>
                {{ $adviser->name }}
                @if($adviser->status === 'active')
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">Active</span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">Inactive</span>
                @endif
            </h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('advisers.edit', $adviser) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Profile
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h2 class="font-bold text-slate-700 text-sm uppercase tracking-wide">
                        <i class="fa-solid fa-id-card text-green-600 mr-2"></i> Faculty Info
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Employee ID</span>
                        <div class="font-mono text-slate-700 font-medium bg-slate-100 inline-block px-2 py-1 rounded text-sm">
                            {{ $adviser->employee_id }}
                        </div>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Email Address</span>
                        <div class="text-slate-700 text-sm font-medium flex items-center gap-2">
                            <i class="fa-regular fa-envelope text-slate-400"></i>
                            <a href="mailto:{{ $adviser->email }}" class="hover:text-green-700 hover:underline transition-colors">
                                {{ $adviser->email }}
                            </a>
                        </div>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Phone Status</span>
                        @if($adviser->phone)
                            <div class="text-slate-700 text-sm font-medium flex items-center gap-2">
                                <i class="fa-solid fa-phone text-slate-400"></i>
                                {{ $adviser->phone }}
                            </div>
                        @else
                            <div class="text-slate-400 text-xs italic">Not registered</div>
                        @endif
                    </div>
                    <div class="pt-4 border-t border-slate-100">
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Class Assignment</span>
                        @php
                            $student = $adviser->advisedStudents->first();
                        @endphp
                        @if($student)
                            <div class="bg-green-50 rounded-lg p-3 border border-green-100">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded bg-green-200 text-green-700 flex items-center justify-center font-bold text-sm shrink-0">
                                        {{ $student->grade_level }}
                                    </div>
                                    <div>
                                        <div class="text-xs text-green-800 font-bold uppercase tracking-tight">Section Name</div>
                                        <div class="text-green-900 font-bold">{{ $student->section }}</div>
                                        <div class="text-[10px] text-green-700 mt-1">
                                            <i class="fa-solid fa-users mr-1"></i> {{ $adviser->advisedStudents->count() }} Students
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 text-center">
                                <p class="text-gray-500 text-sm italic">No section assigned yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Class Roster & Stats -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Stats -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-800">{{ $adviser->advisedStudents->count() }}</div>
                        <div class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total Students</div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-800">
                             {{-- Calculate total active incidents for this class --}}
                             @php
                                $incidentCount = \DB::table('incident_students')
                                    ->whereIn('student_id', $adviser->advisedStudents->pluck('id'))
                                    ->count();
                             @endphp
                             {{ $incidentCount }}
                        </div>
                        <div class="text-xs font-medium text-slate-500 uppercase tracking-wide">Class Incidents</div>
                    </div>
                </div>
            </div>

            <!-- Student List -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h2 class="font-bold text-slate-700 text-sm uppercase tracking-wide">
                        <i class="fa-solid fa-list-ul text-slate-500 mr-2"></i> Class Roster
                    </h2>
                    @if($adviser->advisedStudents->isNotEmpty())
                        <span class="text-xs font-medium text-slate-500 bg-white border border-slate-200 px-2 py-1 rounded">
                            Grade {{ $student->grade_level }} - {{ $student->section }}
                        </span>
                    @endif
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Student ID</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Name</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Gender</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase text-gray-400 tracking-wider text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($adviser->advisedStudents as $advisedStudent)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-3 font-mono text-xs text-slate-600">
                                    {{ $advisedStudent->student_id }}
                                </td>
                                <td class="px-6 py-3 font-medium text-slate-700">
                                    {{ $advisedStudent->last_name }}, {{ $advisedStudent->first_name }}
                                </td>
                                <td class="px-6 py-3 text-slate-500 capitalize text-xs">
                                    {{ $advisedStudent->gender }}
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <a href="{{ route('students.show', $advisedStudent) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 opacity-0 group-hover:opacity-100 transition-opacity uppercase tracking-wide">
                                        View Profile
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400 italic text-sm">
                                    No students currently assigned to this adviser.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
