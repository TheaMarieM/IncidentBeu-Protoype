@extends('layouts.parent')

@section('content')
    <div class="max-w-6xl">
        <!-- Student Header -->
        <div class="bg-white rounded-lg card-shadow p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ $student->first_name }} {{ $student->middle_name ? $student->middle_name[0] . '. ' : '' }}{{ $student->last_name }}
                        </h1>
                        <span class="inline-block bg-teal-100 text-teal-800 text-sm font-semibold px-3 py-1 rounded-full">
                            Grade {{ $student->grade_level }}
                        </span>
                    </div>
                    <p class="text-gray-600">
                        <span class="font-medium">ID:</span> {{ $student->student_id }} | 
                        <span class="font-medium">Section:</span> {{ $student->section }} |
                        <span class="font-medium">Academic Year:</span> {{ $academicYear }}
                    </p>
                </div>
                <a href="{{ route('parent.dashboard') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Children
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 gap-6 mb-6">
            <!-- Total Absences Card -->
            <div class="bg-white rounded-lg card-shadow p-6 {{ $totalAbsences >= 10 ? 'border-2 border-red-500' : 'border border-gray-200' }}">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium mb-1">TOTAL ABSENCES</p>
                        <p class="text-4xl font-bold {{ $totalAbsences >= 10 ? 'text-red-600' : 'text-gray-900' }}">
                            {{ str_pad($totalAbsences, 2, '0', STR_PAD_LEFT) }}
                        </p>
                        @if ($totalAbsences >= 10)
                            <p class="text-sm text-red-600 mt-3 font-semibold">
                                <i class="fas fa-exclamation-triangle"></i> Action Required: High risk status
                            </p>
                        @else
                            <p class="text-xs text-gray-500 mt-2">Current semester</p>
                        @endif
                    </div>
                    <div class="text-5xl {{ $totalAbsences >= 10 ? 'text-red-200' : 'text-gray-200' }}">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                </div>
            </div>

            <!-- Total Tardiness Card -->
            <div class="bg-white rounded-lg card-shadow p-6 border border-gray-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium mb-1">TOTAL TARDINESS</p>
                        <p class="text-4xl font-bold text-gray-900">
                            {{ str_pad($totalTardiness, 2, '0', STR_PAD_LEFT) }}
                        </p>
                        <p class="text-xs text-gray-500 mt-2">Current semester</p>
                    </div>
                    <div class="text-5xl text-gray-200">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Attendance Records -->
            <div class="bg-white rounded-lg card-shadow overflow-hidden">
                <div class="bg-green-50 border-b border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-list text-green-600 mr-2"></i>Recent Attendance
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    @if ($attendanceRecords->count() === 0)
                        <p class="text-gray-500 text-center py-6 text-sm">No attendance records</p>
                    @else
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendanceRecords as $record)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm">
                                            {{ $record->date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($record->status === 'present')
                                                <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded">Present</span>
                                            @elseif ($record->status === 'absent')
                                                <span class="inline-block bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded">Absent</span>
                                            @elseif ($record->status === 'tardy')
                                                <span class="inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded">Tardy</span>
                                            @else
                                                <span class="inline-block bg-gray-100 text-gray-800 text-xs font-semibold px-3 py-1 rounded">{{ ucfirst($record->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- Incidents -->
            <div class="bg-white rounded-lg card-shadow overflow-hidden">
                <div class="bg-yellow-50 border-b border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-exclamation-circle text-yellow-600 mr-2"></i>Behavioral Incidents
                    </h3>
                </div>
                <div class="p-6">
                    @if ($incidents->count() === 0)
                        <p class="text-gray-500 text-center py-6 text-sm">No behavioral incidents recorded</p>
                    @else
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            @foreach ($incidents as $incident)
                                <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-yellow-400">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $incident->category->name ?? 'General Incident' }}</p>
                                            <p class="text-xs text-gray-500">{{ $incident->incident_date->format('M d, Y') }}</p>
                                        </div>
                                        <span class="status-{{ strtolower($incident->status) }}">
                                            {{ ucfirst(str_replace('_', ' ', $incident->status)) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-700">{{ Str::limit($incident->description, 100) }}</p>
                                    @if ($incident->reporter)
                                        <p class="text-xs text-gray-500 mt-2">
                                            Reporter: {{ $incident->reporter->first_name }} {{ $incident->reporter->last_name }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
