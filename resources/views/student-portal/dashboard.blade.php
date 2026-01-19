@extends('layouts.student')

@section('title', 'My Attendance')

@section('content')
<!-- Header -->
<header class="bg-white border-b border-gray-200 px-8 py-5 sticky top-0 z-40">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-800">My Attendance Profile</h2>
            <p class="text-xs text-gray-500 font-medium mt-0.5">
                ID No: {{ $student->student_id }} | Section {{ $student->section }}
            </p>
        </div>
        <div class="bg-gray-50 px-4 py-2 rounded-lg border border-gray-200">
            <div class="flex items-center gap-2 text-xs">
                <i class="fa-solid fa-calendar text-gray-400"></i>
                <span class="font-bold text-gray-600">Academic Year {{ $academicYear }}</span>
            </div>
        </div>
    </div>
</header>

<div class="p-8 max-w-7xl mx-auto">
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Total Absences -->
        <div class="bg-white p-6 rounded-xl border-2 {{ $totalAbsences >= 10 ? 'border-red-300' : 'border-gray-200' }} card-shadow">
            <div class="flex justify-between items-start mb-3">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">My Total Absences</p>
                <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
                    <i class="fa-solid fa-calendar-xmark text-red-500 text-sm"></i>
                </div>
            </div>
            <h4 class="text-5xl font-bold text-gray-900 mb-1">{{ str_pad($totalAbsences, 2, '0', STR_PAD_LEFT) }}</h4>
            <p class="text-xs text-gray-500">Days missed this semester</p>
            @if($totalAbsences >= 10)
                <div class="mt-3 bg-red-50 border border-red-200 rounded-lg px-3 py-2">
                    <p class="text-xs text-red-700 font-semibold">
                        <i class="fa-solid fa-triangle-exclamation"></i> 
                        You are at risk of being dropped due to excessive absences
                    </p>
                </div>
            @endif
        </div>

        <!-- Total Tardiness -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 card-shadow">
            <div class="flex justify-between items-start mb-3">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">My Total Tardiness</p>
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                    <i class="fa-solid fa-clock text-amber-500 text-sm"></i>
                </div>
            </div>
            <h4 class="text-5xl font-bold text-gray-900 mb-1">{{ str_pad($totalTardiness, 2, '0', STR_PAD_LEFT) }}</h4>
            <p class="text-xs text-gray-500">Late arrivals recorded</p>
        </div>
    </div>

    <!-- Attendance History -->
    <div class="bg-white rounded-xl border border-gray-200 card-shadow overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h3 class="text-base font-bold text-gray-800">Attendance History</h3>
            <p class="text-xs text-gray-500 mt-1">A complete log of your recorded absences and tardiness.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Date</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Incident Type</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Details</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($attendanceHistory as $record)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">{{ $record->date->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $record->date->format('l') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($record->status === 'absent')
                                <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">
                                    ABSENT
                                </span>
                            @elseif($record->status === 'tardy')
                                <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">
                                    TARDINESS
                                </span>
                            @elseif($record->status === 'excused')
                                <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">
                                    EXCUSED
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-800 font-medium">
                                @if($record->status === 'tardy' && $record->time_in)
                                    Arrived at {{ \Carbon\Carbon::parse($record->time_in)->format('h:i A') }}
                                @elseif($record->status === 'absent')
                                    Whole day absence
                                @elseif($record->status === 'excused')
                                    Excused absence
                                @else
                                    —
                                @endif
                            </div>
                            @if($record->remarks)
                                <div class="text-xs text-gray-500 mt-1">{{ $record->remarks }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($record->is_excused)
                                <span class="text-green-600 font-bold text-xs uppercase tracking-wide">Validated</span>
                            @else
                                <span class="text-gray-600 font-bold text-xs uppercase tracking-wide">Recorded</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 text-sm">
                            <i class="fa-solid fa-circle-check text-green-500 text-3xl mb-3"></i>
                            <p class="font-semibold">Perfect Attendance!</p>
                            <p class="text-xs mt-1">You have no recorded absences or tardiness this semester.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attendanceHistory->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 flex justify-between items-center">
            <div class="text-xs text-gray-500">
                Showing {{ $attendanceHistory->firstItem() }}-{{ $attendanceHistory->lastItem() }} of {{ $attendanceHistory->total() }} records
            </div>
            <div class="flex gap-2">
                @if($attendanceHistory->onFirstPage())
                    <span class="px-3 py-1.5 text-xs font-bold text-gray-400 bg-gray-100 rounded cursor-not-allowed">Prev</span>
                @else
                    <a href="{{ $attendanceHistory->previousPageUrl() }}" class="px-3 py-1.5 text-xs font-bold text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-50">Prev</a>
                @endif
                
                <span class="px-3 py-1.5 text-xs font-bold text-white bg-green-700 rounded">{{ $attendanceHistory->currentPage() }}</span>
                
                @if($attendanceHistory->hasMorePages())
                    <a href="{{ $attendanceHistory->nextPageUrl() }}" class="px-3 py-1.5 text-xs font-bold text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-50">Next</a>
                @else
                    <span class="px-3 py-1.5 text-xs font-bold text-gray-400 bg-gray-100 rounded cursor-not-allowed">Next</span>
                @endif
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
