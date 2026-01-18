@extends('layouts.parent')

@section('content')
    <div class="p-8 max-w-7xl mx-auto">
        <!-- Breadcrumb & Back Button -->
        <div class="flex items-center justify-between mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('parent.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-green-600">
                            <i class="fa-solid fa-house mr-2"></i>
                            My Children
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fa-solid fa-chevron-right text-gray-400 text-xs mx-1"></i>
                            <span class="ml-1 text-sm font-medium text-gray-800 md:ml-2">{{ $student->first_name }}'s Details</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <a href="{{ route('parent.dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-green-600 transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Student Header Profile -->
        <div class="bg-white rounded-xl card-shadow overflow-hidden mb-8 border border-gray-100">
            <div class="h-24 bg-gradient-to-r from-green-800 to-green-600"></div>
            <div class="px-8 pb-8">
                <div class="relative flex items-end -mt-12 mb-6">
                    <div class="w-24 h-24 rounded-full border-4 border-white bg-green-100 flex items-center justify-center text-3xl font-bold text-green-700 shadow-md">
                        {{ substr($student->first_name, 0, 1) }}
                    </div>
                    <div class="ml-6 mb-1">
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                            {{ $student->first_name }} {{ $student->middle_name ? $student->middle_name[0] . '. ' : '' }}{{ $student->last_name }}
                            <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-0.5 rounded border border-green-200 uppercase tracking-wide">Grade {{ $student->grade_level }}</span>
                        </h1>
                        <p class="text-sm text-gray-500 font-medium">Student ID: {{ $student->student_id }} • Section: {{ $student->section }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-t border-gray-100 pt-6">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Class Adviser</p>
                        @if($student->adviser)
                            <p class="font-medium text-gray-800"><i class="fa-solid fa-chalkboard-user text-green-600 mr-2"></i> {{ $student->adviser->first_name }} {{ $student->adviser->last_name }}</p>
                            <p class="text-xs text-green-600 ml-6">{{ $student->adviser->email }}</p>
                        @else
                            <p class="text-sm text-gray-500 italic">No adviser assigned</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Academic Year</p>
                        <p class="font-medium text-gray-800"><i class="fa-solid fa-calendar mr-2 text-gray-400"></i> {{ $academicYear }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Status</p>
                        <p class="font-medium text-green-600"><span class="w-2 h-2 rounded-full bg-green-500 inline-block mr-2"></span>Enrolled</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Overview Cards -->
         <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-clock-rotate-left text-green-600"></i> Attendance Overview
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Absences -->
            <div class="bg-white p-6 rounded-xl border {{ $totalAbsences >= 5 ? 'border-red-200 bg-red-50' : 'border-gray-200' }} card-shadow relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-6 opacity-10 group-hover:opacity-20 transition">
                    <i class="fa-solid fa-user-xmark text-6xl {{ $totalAbsences >= 5 ? 'text-red-600' : 'text-gray-400' }}"></i>
                </div>
                <p class="text-xs font-bold {{ $totalAbsences >= 5 ? 'text-red-600' : 'text-gray-500' }} uppercase tracking-wider mb-2">Total Absences</p>
                <div class="flex items-baseline gap-2">
                    <h2 class="text-4xl font-bold {{ $totalAbsences >= 5 ? 'text-red-700' : 'text-gray-900' }}">{{ $totalAbsences }}</h2>
                    <span class="text-xs text-gray-500">records this semester</span>
                </div>
                @if ($totalAbsences >= 5)
                     <div class="mt-4 flex items-start gap-2 text-red-700 bg-red-100 p-3 rounded-lg text-xs font-medium">
                        <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                        <p>High number of absences detected. Please coordinate with the adviser.</p>
                     </div>
                @endif
            </div>

            <!-- Tardiness -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 card-shadow relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-6 opacity-10 group-hover:opacity-20 transition">
                    <i class="fa-solid fa-user-clock text-6xl text-amber-500"></i>
                </div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Total Tardiness</p>
                <div class="flex items-baseline gap-2">
                    <h2 class="text-4xl font-bold text-gray-900">{{ $totalTardiness }}</h2>
                    <span class="text-xs text-gray-500">records this semester</span>
                </div>
                <p class="mt-4 text-xs text-amber-600 font-medium flex items-center gap-1">
                    <i class="fa-regular fa-lightbulb"></i> Tip: Regular attendance improves academic performance.
                </p>
            </div>
        </div>

        <!-- Detailed Records Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
             <!-- Attendance Table -->
            <div class="bg-white rounded-xl border border-gray-200 card-shadow overflow-hidden flex flex-col h-full">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                    <h4 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Recent Attendance Log</h4>
                    <span class="text-xs text-gray-500">Last 10 records</span>
                </div>
                <div class="flex-1 overflow-x-auto">
                    @if ($attendanceRecords->count() === 0)
                        <div class="flex flex-col items-center justify-center h-40 text-center p-6">
                            <i class="fa-regular fa-calendar-check text-gray-300 text-3xl mb-2"></i>
                            <p class="text-sm text-gray-500">No attendance records found yet.</p>
                        </div>
                    @else
                        <table class="w-full text-left">
                            <thead class="bg-white border-b border-gray-100 text-xs text-gray-500 uppercase">
                                <tr>
                                    <th class="px-6 py-3 font-semibold">Date</th>
                                    <th class="px-6 py-3 font-semibold">Time In</th>
                                    <th class="px-6 py-3 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 text-sm">
                                @foreach ($attendanceRecords as $record)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-3 font-medium text-gray-800">
                                            {{ $record->date->format('M d, Y') }}
                                            <span class="block text-[10px] text-gray-400 font-normal">{{ $record->date->format('l') }}</span>
                                        </td>
                                        <td class="px-6 py-3 text-gray-600 font-mono text-xs">
                                            @if($record->time_in)
                                                {{ \Carbon\Carbon::parse($record->time_in)->format('h:i A') }}
                                            @else
                                                <span class="text-gray-300">--:--</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3">
                                            @if ($record->status === 'present')
                                                <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded uppercase tracking-wide">Present</span>
                                            @elseif ($record->status === 'absent')
                                                <span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-0.5 rounded uppercase tracking-wide">Absent</span>
                                            @elseif ($record->status === 'tardy')
                                                <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2 py-0.5 rounded uppercase tracking-wide">Tardy</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-700 text-xs font-bold px-2 py-0.5 rounded uppercase tracking-wide">{{ ucfirst($record->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- Incidents List -->
            <div class="bg-white rounded-xl border border-gray-200 card-shadow overflow-hidden flex flex-col h-full">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                    <h4 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Behavioral Incidents</h4>
                    <span class="text-xs text-gray-500">Recent Reports</span>
                </div>
                <div class="p-6 flex-1 overflow-y-auto max-h-[500px]">
                    @if ($incidents->count() === 0)
                        <div class="flex flex-col items-center justify-center h-40 text-center">
                            <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center mb-3">
                                <i class="fa-solid fa-shield-heart text-green-500 text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Great Job!</p>
                            <p class="text-xs text-gray-500 mt-1">No incidents reported for this student.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($incidents as $incident)
                                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition group">
                                    <div class="flex items-start justify-between mb-2">
                                        <p class="text-sm font-bold text-gray-800 group-hover:text-green-700 transition">{{ $incident->category->name ?? 'General Incident' }}</p>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded border 
                                            {{ $incident->status === 'resolved' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-yellow-50 text-yellow-700 border-yellow-100' }}">
                                            {{ ucfirst($incident->status) }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mb-3 flex items-center gap-2">
                                        <i class="fa-regular fa-calendar"></i> {{ $incident->incident_date->format('M d, Y') }}
                                        @if($incident->reporter)
                                            <span class="text-gray-300">|</span>
                                            <span><i class="fa-solid fa-user-pen"></i> {{ $incident->reporter->first_name }} {{ $incident->reporter->last_name }}</span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600 leading-relaxed bg-gray-50 p-3 rounded-lg text-xs italic">
                                        "{{ Str::limit($incident->description, 120) }}"
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
@endsection
