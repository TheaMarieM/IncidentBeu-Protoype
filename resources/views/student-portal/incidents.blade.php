@extends('layouts.student')

@section('title', 'My Incidents')

@section('content')
<!-- Header -->
<header class="bg-white border-b border-gray-200 px-8 py-5 sticky top-0 z-40">
    <div>
        <h2 class="text-xl font-bold text-gray-800">My Behavioral Incidents</h2>
        <p class="text-xs text-gray-500 font-medium mt-0.5">View all behavioral incidents involving you</p>
    </div>
</header>

<div class="p-8 max-w-7xl mx-auto">
    
    <!-- Incidents Table -->
    <div class="bg-white rounded-xl border border-gray-200 card-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Case ID</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Date</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Category</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Description</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Reported By</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($incidents as $incident)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-mono font-bold text-gray-700">{{ str_pad($incident->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">{{ $incident->incident_date->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $incident->incident_date->format('g:i A') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($incident->category)
                                <span class="text-xs font-semibold text-gray-700">{{ $incident->category->name }}</span>
                            @else
                                <span class="text-xs text-gray-400 italic">Not categorized</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-800 max-w-xs">{{ Str::limit($incident->description, 80) }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                <i class="fa-solid fa-location-dot"></i> {{ $incident->location }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-800 font-medium">{{ $incident->reporter->name ?? 'Unknown' }}</div>
                            @if($incident->reporter && $incident->reporter->role)
                                <div class="text-xs text-gray-500 uppercase">{{ $incident->reporter->role->name }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($incident->status === 'reported')
                                <span class="inline-flex items-center gap-1.5 bg-yellow-50 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold uppercase">
                                    <i class="fa-solid fa-clock text-[10px]"></i> Pending
                                </span>
                            @elseif($incident->status === 'under_review')
                                <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold uppercase">
                                    <i class="fa-solid fa-search text-[10px]"></i> Under Review
                                </span>
                            @elseif($incident->status === 'resolved')
                                <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs font-bold uppercase">
                                    <i class="fa-solid fa-check text-[10px]"></i> Resolved
                                </span>
                            @elseif($incident->status === 'dismissed')
                                <span class="inline-flex items-center gap-1.5 bg-gray-50 text-gray-700 px-3 py-1 rounded-full text-xs font-bold uppercase">
                                    <i class="fa-solid fa-ban text-[10px]"></i> Dismissed
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">
                            <i class="fa-solid fa-face-smile text-green-500 text-3xl mb-3"></i>
                            <p class="font-semibold">No Behavioral Incidents</p>
                            <p class="text-xs mt-1">You have no recorded behavioral incidents. Keep up the good behavior!</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($incidents->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 flex justify-between items-center">
            <div class="text-xs text-gray-500">
                Showing {{ $incidents->firstItem() }}-{{ $incidents->lastItem() }} of {{ $incidents->total() }} incidents
            </div>
            {{ $incidents->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
