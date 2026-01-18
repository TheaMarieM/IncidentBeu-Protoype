@extends('layouts.parent')

@section('content')
    <div class="p-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Children List -->
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">My Children</h3>
                    <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full">{{ $children->count() }} Student(s)</span>
                </div>

                @if ($children->count() === 0)
                    <div class="bg-white rounded-lg card-shadow p-8 text-center">
                        <i class="fas fa-inbox text-gray-400 text-5xl mb-4 block"></i>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Children Found</h3>
                        <p class="text-gray-600">You don't have any linked children in the system yet. Please contact the school to link your child/children.</p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach ($children as $child)
                            <div class="bg-white rounded-xl card-shadow overflow-hidden border border-gray-100 transition hover:shadow-md">
                                <!-- Header with child info -->
                                <div class="bg-white p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-lg">
                                            {{ substr($child->first_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <h3 class="text-lg font-bold text-gray-900">
                                                    {{ $child->first_name }} {{ $child->middle_name ? $child->middle_name[0] . '. ' : '' }}{{ $child->last_name }}
                                                </h3>
                                                <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wide">
                                                    Grade {{ $child->grade_level }}
                                                </span>
                                            </div>
                                            <div class="text-xs text-gray-500 flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
                                                <span><i class="fa-solid fa-id-card mr-1"></i> {{ $child->student_id }}</span>
                                                <span class="hidden md:inline text-gray-300">|</span>
                                                <span><i class="fa-solid fa-layer-group mr-1"></i> {{ $child->section }}</span>
                                                @if ($child->adviser)
                                                    <span class="hidden md:inline text-gray-300">|</span>
                                                    <span><i class="fa-solid fa-chalkboard-user mr-1"></i> Adviser: {{ $child->adviser->first_name }} {{ $child->adviser->last_name }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('parent.view-child', $child->student_id) }}" class="flex-shrink-0 text-sm font-semibold text-green-700 hover:text-green-800 hover:underline flex items-center gap-1">
                                        View Details <i class="fa-solid fa-chevron-right text-xs"></i>
                                    </a>
                                </div>

                                <!-- Attendance Stats -->
                                <div class="grid grid-cols-2 divide-x divide-gray-100 bg-gray-50/50">
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

                                    <div class="p-4 text-center">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Absences</p>
                                        <p class="text-2xl font-bold {{ $totalAbsences >= 10 ? 'text-red-600' : 'text-gray-800' }}">
                                            {{ str_pad($totalAbsences, 2, '0', STR_PAD_LEFT) }}
                                        </p>
                                        @if ($totalAbsences >= 10)
                                            <p class="text-[10px] text-red-600 font-bold mt-1 bg-red-50 inline-block px-2 py-0.5 rounded">Action Required</p>
                                        @endif
                                    </div>

                                    <div class="p-4 text-center">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Tardiness</p>
                                        <p class="text-2xl font-bold text-gray-800">
                                            {{ str_pad($totalTardiness, 2, '0', STR_PAD_LEFT) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right Column: Notifications -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl card-shadow border border-gray-200 sticky top-24">
                    <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 rounded-t-xl">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-bell text-amber-500"></i> Notifications
                        </h3>
                        @if($notifications->count() > 0)
                            <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[20px] text-center">{{ $notifications->count() }}</span>
                        @endif
                    </div>
                    
                    <div class="max-h-[500px] overflow-y-auto p-2">
                        @forelse($notifications as $notification)
                            <div class="p-3 mb-2 rounded-lg hover:bg-gray-50 transition border border-transparent hover:border-gray-100 group">
                                <div class="flex gap-3">
                                    <div class="mt-1 flex-shrink-0">
                                        @if($notification->incident_id)
                                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                                <i class="fa-solid fa-file-invoice text-red-600 text-xs"></i>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                <i class="fa-solid fa-comment-dots text-blue-600 text-xs"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-800 leading-snug break-words">
                                            {{ $notification->message }}
                                        </p>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-[10px] text-gray-400 font-medium">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                            @if(!$notification->read_at)
                                                <span class="w-2 h-2 rounded-full bg-amber-500 relative">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 px-4">
                                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-regular fa-bell-slash text-gray-400"></i>
                                </div>
                                <p class="text-sm text-gray-500 font-medium">No new notifications</p>
                                <p class="text-xs text-gray-400 mt-1">We'll let you know when there's an update.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
