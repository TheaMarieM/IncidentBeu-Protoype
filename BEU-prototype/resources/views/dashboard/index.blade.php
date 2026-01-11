@extends('layouts.app')

@section('content')
<!-- Header -->
<header class="bg-white border-b border-gray-200 px-8 py-5 flex justify-between items-center sticky top-0 z-40">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Discipline Chairperson Dashboard</h2>
        <p class="text-xs text-gray-500 font-medium mt-0.5">Welcome back, Administrator</p>
    </div>
    <div class="flex items-center gap-6">
        <a href="{{ route('incidents.create') }}" class="bg-green-700 hover:bg-green-800 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-plus text-xs"></i> Log New Incident
        </a>
        <div class="h-8 w-px bg-gray-200"></div>
        <div class="flex items-center gap-3">
            <div class="text-right">
                <p class="text-sm font-bold text-gray-700">{{ Auth::user()->name ?? 'D. Chairperson' }}</p>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Main Admin</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-500">
                <i class="fa-solid fa-user"></i>
            </div>
        </div>
    </div>
</header>

<div class="p-8 max-w-7xl mx-auto">
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- At-Risk Students Card -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 card-shadow">
            <div class="flex justify-between items-start mb-4">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">At-Risk Students Detected</p>
                <i class="fa-solid fa-triangle-exclamation text-red-500 bg-red-50 p-2 rounded-lg text-xs"></i>
            </div>
            <div class="flex items-baseline gap-2">
                <h4 class="text-3xl font-bold text-gray-900">{{ $atRiskStudentsCount }}</h4>
                <span class="text-red-600 text-[10px] font-bold">Action Required</span>
            </div>
            <p class="text-xs text-gray-400 mt-3 italic leading-relaxed">Early intervention recommended[cite: 269].</p>
        </div>

        <!-- Common Incident Card -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 card-shadow">
            <div class="flex justify-between items-start mb-4">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Common Incident (Q4)</p>
                <i class="fa-solid fa-clock text-blue-500 bg-blue-50 p-2 rounded-lg text-xs"></i>
            </div>
            @if($mostCommonIncident)
                <h4 class="text-xl font-bold text-gray-900">{{ $mostCommonIncident->name ?? 'No data' }}</h4>
                <p class="text-xs text-gray-500 mt-2 font-medium">Primary trend in Grade 9 & 10[cite: 268].</p>
            @else
                <h4 class="text-xl font-bold text-gray-900">No data</h4>
                <p class="text-xs text-gray-500 mt-2 font-medium">No incidents recorded this quarter.</p>
            @endif
        </div>

        <!-- Pending Approvals Card -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 card-shadow">
            <div class="flex justify-between items-start mb-4">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Pending Approvals</p>
                <i class="fa-solid fa-file-signature text-amber-500 bg-amber-50 p-2 rounded-lg text-xs"></i>
            </div>
            <div class="flex items-baseline gap-2">
                <h4 class="text-3xl font-bold text-gray-900">{{ str_pad($pendingApprovalsCount, 2, '0', STR_PAD_LEFT) }}</h4>
                <span class="text-amber-600 text-[10px] font-bold">Awaiting Principal</span>
            </div>
            <p class="text-xs text-gray-500 mt-3 font-medium">Requires final case closure.</p>
        </div>
    </div>

    <!-- Recent Incidents Table -->
    <div class="bg-white rounded-xl border border-gray-200 card-shadow overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Recent Behavioral Incidents</h3>
            <div class="flex gap-2">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                    <input type="text" placeholder="Search record..." class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-xs focus:ring-1 focus:ring-green-500 outline-none w-64 bg-gray-50">
                </div>
                <button class="px-4 py-2 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-50">
                    <i class="fa-solid fa-sliders mr-2"></i> Filters
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Date/Time</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Student Name</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Violation Type</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Reported By</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($recentIncidents as $incident)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $incident->incident_date->format('M d, Y h:i A') }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-800">
                            @if($incident->students->isNotEmpty())
                                {{ $incident->students->first()->full_name }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $incident->category->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-gray-500 italic">{{ $incident->reportedBy->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            @if($incident->status === 'pending_approval')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100 uppercase">Pending Review</span>
                            @elseif($incident->status === 'approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-700 border border-green-100 uppercase">Approved</span>
                            @elseif($incident->status === 'rejected')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-100 uppercase">Rejected</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-50 text-gray-700 border border-gray-100 uppercase">{{ $incident->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('incidents.show', $incident) }}" class="text-green-700 font-bold text-xs hover:text-green-900 underline underline-offset-4 tracking-tight">View Details</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">
                            No incidents recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- AI Suggestions -->
    <div class="bg-gray-900 rounded-xl p-8 text-white relative overflow-hidden card-shadow">
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg bg-green-500 flex items-center justify-center text-white text-sm">
                    <i class="fa-solid fa-lightbulb"></i>
                </div>
                <h3 class="font-bold text-lg">AI-Driven Intervention Suggestions</h3>
            </div>
            
            @if($suggestions && $suggestions->isNotEmpty())
                @foreach($suggestions as $suggestion)
                <div class="max-w-3xl mb-6 last:mb-0">
                    <p class="text-green-300 text-[11px] font-bold uppercase tracking-widest mb-1">System Insight: {{ $suggestion->grade_level ?? 'General' }}</p>
                    <p class="text-gray-300 text-sm leading-relaxed mb-6">
                        {{ $suggestion->suggestion }} [cite: {{ $suggestion->incident_count ?? '269' }}].
                    </p>
                    <div class="flex gap-4">
                        <button class="bg-green-600 hover:bg-green-500 text-white px-6 py-2 rounded-lg text-xs font-bold transition-all shadow-lg">Approve Recommendation</button>
                        <button class="bg-transparent border border-gray-700 hover:bg-gray-800 text-gray-400 px-6 py-2 rounded-lg text-xs font-bold transition-all">Dismiss Suggestion</button>
                    </div>
                </div>
                @endforeach
            @else
                <div class="max-w-3xl">
                    <p class="text-green-300 text-[11px] font-bold uppercase tracking-widest mb-1">System Insight: General</p>
                    <p class="text-gray-300 text-sm leading-relaxed mb-6">
                        No AI suggestions available at this time. The system will analyze incident patterns and provide recommendations.
                    </p>
                </div>
            @endif
        </div>
        <div class="absolute right-0 top-0 w-64 h-full bg-gradient-to-l from-green-500/10 to-transparent"></div>
    </div>

</div>
@endsection
