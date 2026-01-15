@extends('layouts.principal')

@section('content')
@php
    $userName = auth()->user()->name ?? 'Sr. Principal';
    $userRole = auth()->user()->role->name ?? 'principal';
    $roleDisplayName = $userRole === 'assistant_principal' ? 'Asst. Principal' : 'Principal';
    $totalDisplay = str_pad($totalArchived, 2, '0', STR_PAD_LEFT);
    $monthDisplay = str_pad($archivedThisMonth, 2, '0', STR_PAD_LEFT);
    $statusMeta = [
        'approved' => ['label' => 'Closed', 'class' => 'bg-emerald-50 text-emerald-700 border border-emerald-200'],
    ];
@endphp

<header class="bg-white border-b border-gray-200 px-8 py-4 flex flex-wrap gap-4 justify-between items-center sticky top-0 z-30 shadow-sm">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Case Archives</h1>
        <p class="text-sm text-gray-500 mt-1">Closed and approved incidents</p>
    </div>
    <div class="flex items-center gap-4">
        <a href="{{ route('principal.dashboard') }}" class="px-6 py-3 text-sm font-semibold rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors inline-flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
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
    <section class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="bg-white border border-gray-200 rounded-3xl p-6 card-shadow">
            <div class="flex items-center justify-between">
                <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold">Total Archived Cases</p>
                <span class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-check-circle"></i>
                </span>
            </div>
            <h3 class="text-4xl font-black text-emerald-600 mt-3">{{ $totalDisplay }}</h3>
            <p class="text-xs text-emerald-500 font-semibold">Closed Cases</p>
            <p class="text-xs text-gray-500 mt-2">All approved and finalized incidents.</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-3xl p-6 card-shadow">
            <div class="flex items-center justify-between">
                <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold">Archived This Month</p>
                <span class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-calendar-check"></i>
                </span>
            </div>
            <h3 class="text-4xl font-black text-blue-600 mt-3">{{ $monthDisplay }}</h3>
            <p class="text-xs text-blue-500 font-semibold">Monthly Total</p>
            <p class="text-xs text-gray-500 mt-2">Cases closed in {{ now()->format('F Y') }}.</p>
        </div>
    </section>

    <section class="bg-white border border-gray-200 rounded-3xl overflow-hidden card-shadow">
        <div class="px-7 py-6 border-b border-gray-100 flex flex-wrap gap-3 justify-between items-center">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold">Archived Records</p>
                <h2 class="text-2xl font-black text-gray-900 mt-1">Closed Cases</h2>
                <p class="text-xs text-gray-500 mt-1">View-only records of approved incidents.</p>
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
                        <th class="px-6 py-4 text-left">Date Closed</th>
                        <th class="px-6 py-4 text-left">Student Name</th>
                        <th class="px-6 py-4 text-left">Violation Type</th>
                        <th class="px-6 py-4 text-left">Reported By</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($archivedIncidents as $incident)
                        @php
                            $meta = $statusMeta['approved'];
                            $primaryStudent = $incident->students->first()?->full_name;
                            $additionalCount = max(0, $incident->students->count() - 1);
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-semibold text-gray-800">
                                {{ optional($incident->updated_at)->format('M d, Y · h:i A') ?? '—' }}
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
                                <a href="{{ route('principal.incidents.show', $incident) }}" class="inline-flex items-center gap-2 text-green-700 font-semibold text-xs hover:underline">
                                    View Details <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400 text-sm">No archived cases found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $archivedIncidents->links() }}
        </div>
    </section>
</div>
@endsection
