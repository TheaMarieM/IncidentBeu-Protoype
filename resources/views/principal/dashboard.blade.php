@extends('layouts.principal')

@section('content')
@php
    $userName = auth()->user()->name ?? 'Sr. Principal';
    $userRole = auth()->user()->role->name ?? 'principal';
    $dashboardTitle = $userRole === 'assistant_principal' ? "Assistant Principal's Dashboard" : "Principal's Dashboard";
    $roleDisplayName = $userRole === 'assistant_principal' ? 'Asst. Principal' : 'Principal';
    $atRiskDisplay = str_pad($atRiskStudentsCount, 2, '0', STR_PAD_LEFT);
    $pendingDisplay = str_pad($pendingApprovalsCount, 2, '0', STR_PAD_LEFT);
    $commonIncidentCopy = $commonIncidentTotal > 0
        ? $commonIncidentName . ' · ' . $commonIncidentTotal . ' cases'
        : 'No active violation trend';
    $statusMeta = [
        'pending_approval' => ['label' => 'Awaiting Principal', 'class' => 'bg-amber-50 text-amber-700 border border-amber-200'],
        'under_review' => ['label' => 'Under Review', 'class' => 'bg-gray-100 text-gray-600 border border-gray-200'],
        'reported' => ['label' => 'Reported', 'class' => 'bg-blue-50 text-blue-600 border border-blue-200'],
        'approved' => ['label' => 'Closed', 'class' => 'bg-emerald-50 text-emerald-700 border border-emerald-200'],
    ];
@endphp

<header class="bg-gradient-to-r from-green-800 to-green-700 border-b border-green-900 px-8 py-6 flex flex-wrap gap-4 justify-between items-center sticky top-0 z-30 shadow-lg">
    <div>
        <h1 class="text-2xl font-black text-white">{{ $dashboardTitle }}</h1>
        <p class="text-sm text-green-100 mt-1">Welcome back, {{ $userName }}</p>
    </div>
    <div class="flex items-center gap-4">
        <a href="#incidents-overview" class="px-6 py-3 text-sm font-bold rounded-xl bg-white text-green-800 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">
            <i class="fa-solid fa-clipboard-check"></i> Review Incidents
        </a>
        <div class="h-10 w-px bg-green-600"></div>
        <div class="flex items-center gap-3">
            <p class="text-sm font-bold text-white">{{ $roleDisplayName }}</p>
            <div class="w-11 h-11 rounded-xl bg-white/20 backdrop-blur-sm border-2 border-white/30 flex items-center justify-center text-white">
                <i class="fa-solid fa-user-tie"></i>
            </div>
        </div>
    </div>
</header>

<div class="p-8 space-y-8">
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-red-50 to-rose-50 border-2 border-red-100 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs font-bold text-red-400 uppercase tracking-wider">At-Risk Students</p>
                    <h3 class="text-4xl font-black text-red-600 mt-2">{{ $atRiskDisplay }}</h3>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation text-red-600 text-xl"></i>
                </div>
            </div>
            <div class="bg-red-100/50 rounded-lg px-3 py-2">
                <p class="text-xs text-red-700 font-bold">⚠️ Action Required</p>
                <p class="text-xs text-red-600 mt-1">Early intervention recommended</p>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 border-2 border-blue-100 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs font-bold text-blue-400 uppercase tracking-wider">Common Incident (Q4)</p>
                    <h3 class="text-lg font-black text-blue-900 mt-2 leading-tight">{{ $commonIncidentName }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-chart-line text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-blue-600 font-medium">{{ $commonIncidentCopy }}</p>
        </div>
        
        <div class="bg-gradient-to-br from-amber-50 to-yellow-50 border-2 border-amber-100 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs font-bold text-amber-400 uppercase tracking-wider">Pending Approvals</p>
                    <h3 class="text-4xl font-black text-amber-600 mt-2">{{ $pendingDisplay }}</h3>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-clipboard-check text-amber-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-amber-700 font-bold">📋 Awaiting Principal Review</p>
            <p class="text-xs text-amber-600 mt-1">Requires final case closure</p>
        </div>
    </section>

    <section class="bg-white border-2 border-gray-200 rounded-2xl overflow-hidden shadow-xl" id="incidents-overview">
        <div class="px-7 py-6 bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200 flex flex-wrap gap-3 justify-between items-center">
            <div>
                <p class="text-xs uppercase tracking-wider text-gray-500 font-bold">Incidents Overview</p>
                <h2 class="text-2xl font-black text-gray-900 mt-1">Cases Routed for Admin Review</h2>
                <p class="text-xs text-gray-600 mt-1">Track real-time submissions, severity, and routing officer</p>
            </div>
                <div class="flex gap-3">
                    <button class="px-5 py-2.5 text-xs font-bold border-2 border-gray-200 rounded-xl text-gray-700 hover:bg-white transition-colors bg-gray-50">
                        <i class="fa-solid fa-magnifying-glass mr-2"></i> Search
                    </button>
                    <button class="px-5 py-2.5 text-xs font-bold border-2 border-gray-200 rounded-xl text-gray-700 hover:bg-white transition-colors bg-gray-50">
                        <i class="fa-solid fa-filter mr-2"></i> Filters
                    </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-green-50 to-emerald-50 text-xs uppercase tracking-wider text-green-800 border-b-2 border-green-100">
                    <tr>
                        <th class="px-6 py-4 text-left font-black">Date / Time</th>
                        <th class="px-6 py-4 text-left font-black">Student Name</th>
                        <th class="px-6 py-4 text-left font-black">Violation Type</th>
                        <th class="px-6 py-4 text-left font-black">Reported By</th>
                        <th class="px-6 py-4 text-left font-black">Status</th>
                        <th class="px-6 py-4 text-left font-black">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($incidentsOverview as $incident)
                        @php
                            $meta = $statusMeta[$incident->status] ?? ['label' => ucfirst(str_replace('_', ' ', $incident->status)), 'class' => 'bg-gray-100 text-gray-600 border-2 border-gray-200'];
                            $primaryStudent = $incident->students->first()?->full_name;
                            $additionalCount = max(0, $incident->students->count() - 1);
                        @endphp
                        <tr class="hover:bg-green-50/30 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-700">
                                {{ optional($incident->incident_date)->format('M d, Y') ?? '—' }}<br>
                                <span class="text-xs text-gray-500">{{ optional($incident->incident_date)->format('h:i A') ?? '' }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900">
                                {{ $primaryStudent ?? 'Student record withheld' }}
                                @if($additionalCount > 0)
                                    <br><span class="text-xs text-gray-500 font-normal">+{{ $additionalCount }} more</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-700 font-semibold">{{ $incident->category->name ?? 'Uncategorized' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $incident->reporter->name ?? 'Discipline Office' }}</td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-black uppercase tracking-wider px-3 py-1.5 rounded-full {{ $meta['class'] }}">{{ $meta['label'] }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('principal.incidents.show', $incident) }}" class="inline-flex items-center gap-2 text-green-700 font-bold text-sm hover:text-green-900 hover:gap-3 transition-all">
                                    View Details <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fa-solid fa-inbox text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500 font-semibold">No incidents routed for approval yet</p>
                                    <p class="text-gray-400 text-sm mt-1">All clear for now!</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t-2 border-gray-100 bg-gray-50">
            {{ $incidentsOverview->links() }}
        </div>
    </section>
</div>
@endsection
