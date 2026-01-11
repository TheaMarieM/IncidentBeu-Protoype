@extends('layouts.parent')

@section('content')
    <div class="max-w-6xl mx-auto">
        @if ($children->count() === 0)
            <div class="bg-white rounded-lg card-shadow p-8 text-center">
                <i class="fas fa-inbox text-gray-400 text-5xl mb-4 block"></i>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Children Found</h3>
                <p class="text-gray-600">You don't have any linked children in the system yet. Please contact the school to link your child/children.</p>
            </div>
        @else
            <!-- Children Grid -->
            <div class="grid grid-cols-1 gap-6">
                @foreach ($children as $child)
                    <div class="bg-white rounded-lg card-shadow overflow-hidden">
                        <!-- Header with child info -->
                        <div class="bg-green-50 border-l-4 border-green-600 p-6 flex items-center justify-between">
                            <div>
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-xl font-bold text-gray-900">
                                        {{ $child->first_name }} {{ $child->middle_name ? $child->middle_name[0] . '. ' : '' }}{{ $child->last_name }}
                                    </h3>
                                    <span class="inline-block bg-teal-100 text-teal-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        Grade {{ $child->grade_level }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-id-card"></i> ID: {{ $child->student_id }} | Section: {{ $child->section }}
                                </p>
                                @if ($child->adviser)
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-chalkboard-user"></i> Adviser: {{ $child->adviser->first_name }} {{ $child->adviser->last_name }}
                                    </p>
                                @endif
                            </div>
                            <a href="{{ route('parent.view-child', $child->student_id) }}"
                                class="btn-primary">
                                <i class="fas fa-eye mr-2"></i>View Details
                            </a>
                        </div>

                        <!-- Attendance Stats -->
                        <div class="grid grid-cols-2 gap-4 p-6 border-t border-gray-100">
                            @php
                                $currentYear = now()->year;
                                $currentMonth = now()->month;
                                
                                if ($currentMonth >= 8) {
                                    $semesterStart = now()->setMonth(8)->startOfMonth();
                                    $semesterEnd = now()->setMonth(12)->endOfMonth();
                                } else {
                                    $semesterStart = now()->setMonth(1)->startOfMonth();
                                    $semesterEnd = now()->setMonth(5)->endOfMonth();
                                }
                                
                                $totalAbsences = \App\Models\AttendanceRecord::where('student_id', $child->id)
                                    ->where('status', 'absent')
                                    ->whereBetween('date', [$semesterStart, $semesterEnd])
                                    ->count();
                                
                                $totalTardiness = \App\Models\AttendanceRecord::where('student_id', $child->id)
                                    ->where('status', 'tardy')
                                    ->whereBetween('date', [$semesterStart, $semesterEnd])
                                    ->count();
                            @endphp

                            <!-- Total Absences Card -->
                            <div class="bg-gray-50 rounded-lg p-4 {{ $totalAbsences >= 10 ? 'border-2 border-red-500' : 'border border-gray-200' }}">
                                <p class="text-xs text-gray-600 mb-1">TOTAL ABSENCES</p>
                                <p class="text-3xl font-bold {{ $totalAbsences >= 10 ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ str_pad($totalAbsences, 2, '0', STR_PAD_LEFT) }}
                                </p>
                                @if ($totalAbsences >= 10)
                                    <p class="text-xs text-red-600 mt-2 font-semibold">
                                        <i class="fas fa-exclamation-triangle"></i> Action Required: High risk status
                                    </p>
                                @endif
                            </div>

                            <!-- Total Tardiness Card -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <p class="text-xs text-gray-600 mb-1">TOTAL TARDINESS</p>
                                <p class="text-3xl font-bold text-gray-900">
                                    {{ str_pad($totalTardiness, 2, '0', STR_PAD_LEFT) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
