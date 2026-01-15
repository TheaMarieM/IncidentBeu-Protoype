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

<header class="bg-white border-b border-gray-200 px-8 py-4 flex flex-wrap gap-4 justify-between items-center sticky top-0 z-30 shadow-sm">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $dashboardTitle }}</h1>
        <p class="text-sm text-gray-500 mt-1">Welcome back, {{ $userName }}</p>
    </div>
    <div class="flex items-center gap-4">
        <a href="#incidents-overview" class="px-6 py-3 text-sm font-semibold rounded-lg bg-green-700 text-white shadow hover:bg-green-800 transition-colors inline-flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Review Incidents
        </a>
        <div class="h-10 w-px bg-gray-300"></div>
        <div class="flex items-center gap-3">
            <p class="text-sm font-bold text-gray-900">{{ $roleDisplayName }}</p>
            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                <i class="fa-solid fa-user"></i>
            </div>
        </div>
    </div>
</header>

<div class="p-8 space-y-8">
    <section class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white border border-gray-200 rounded-3xl p-6 card-shadow">
            <div class="flex items-center justify-between">
                <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold">At-Risk Students Detected</p>
                <span class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </span>
            </div>
            <h3 class="text-4xl font-black text-rose-600 mt-3">{{ $atRiskDisplay }}</h3>
            <p class="text-xs text-rose-500 font-semibold">Action Required</p>
            <p class="text-xs text-gray-500 mt-2">Early intervention recommended.</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-3xl p-6 card-shadow">
            <div class="flex items-center justify-between">
                <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold">Common Incident (Q4)</p>
                <span class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-bullseye"></i>
                </span>
            </div>
            <h3 class="text-2xl font-black text-gray-900 mt-3 leading-snug">{{ $commonIncidentName }}</h3>
            <p class="text-xs text-gray-500 mt-2">{{ $commonIncidentCopy }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-3xl p-6 card-shadow">
            <div class="flex items-center justify-between">
                <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold">Pending Approvals</p>
                <span class="w-10 h-10 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center">
                    <i class="fa-solid fa-clipboard-check"></i>
                </span>
            </div>
            <h3 class="text-4xl font-black text-amber-600 mt-3">{{ $pendingDisplay }}</h3>
            <p class="text-xs text-amber-500 font-semibold">Awaiting Principal</p>
            <p class="text-xs text-gray-500 mt-2">Requires final case closure.</p>
        </div>
    </section>

    <section class="bg-white border border-gray-200 rounded-3xl overflow-hidden card-shadow" id="incidents-overview">
        <div class="px-7 py-6 border-b border-gray-100 flex flex-wrap gap-3 justify-between items-center">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold">Incidents Overview</p>
                <h2 class="text-2xl font-black text-gray-900 mt-1">Cases Routed for Admin Review</h2>
                <p class="text-xs text-gray-500 mt-1">Track real-time submissions, severity, and routing officer.</p>
            </div>
                <div class="flex gap-2">
                    <button class="px-4 py-2 text-xs font-semibold border border-gray-200 rounded-full text-gray-600 hover:bg-gray-50">
                        <i class="fa-solid fa-magnifying-glass mr-2"></i> Search Record
                    </button>
                    <button class="px-4 py-2 text-xs font-semibold border border-gray-200 rounded-full text-gray-600 hover:bg-gray-50">
                        <i class="fa-solid fa-filter mr-2"></i> Filters
                    </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-[10px] uppercase tracking-[0.2em] text-gray-500">
                    <tr>
                        <th class="px-6 py-4 text-left">Date / Time</th>
                        <th class="px-6 py-4 text-left">Student Name</th>
                        <th class="px-6 py-4 text-left">Violation Type</th>
                        <th class="px-6 py-4 text-left">Reported By</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($incidentsOverview as $incident)
                        @php
                            $meta = $statusMeta[$incident->status] ?? ['label' => ucfirst(str_replace('_', ' ', $incident->status)), 'class' => 'bg-gray-100 text-gray-600 border border-gray-200'];
                            $primaryStudent = $incident->students->first()?->full_name;
                            $additionalCount = max(0, $incident->students->count() - 1);
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-semibold text-gray-800">
                                {{ optional($incident->incident_date)->format('M d, Y · h:i A') ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $primaryStudent ?? 'Student record withheld' }}
                                @if($additionalCount > 0)
                                    <span class="text-xs text-gray-400">+{{ $additionalCount }} more</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $incident->category->name ?? 'Uncategorized' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $incident->reporter->name ?? 'Discipline Office' }}</td>
                            <td class="px-6 py-4">
                                <span class="text-[10px] font-semibold uppercase tracking-[0.2em] px-3 py-1 rounded-full {{ $meta['class'] }}">{{ $meta['label'] }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('principal.incidents.show', $incident) }}" class="inline-flex items-center gap-2 text-green-700 font-semibold text-xs hover:underline" aria-label="Open violation report for case {{ $incident->incident_number ?? 'N/A' }}">
                                    View Details <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400 text-sm">No incidents have been routed for your approval yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $incidentsOverview->links() }}
        </div>
    </section>
</div>
@endsection
