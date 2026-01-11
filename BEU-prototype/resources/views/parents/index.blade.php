@extends('layouts.app')

@section('content')
<!-- Header -->
<header class="bg-white border-b border-gray-200 px-8 py-5 flex justify-between items-center sticky top-0 z-40">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Parent & Guardian Registry</h2>
        <p class="text-xs text-gray-500 font-medium mt-0.5">Manage communication and monitoring access for families [cite: 290]</p>
    </div>
    <div>
        <div class="text-right">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Admin Authorization</p>
            <p class="text-xs font-bold text-green-600">VIEW & NOTIFY ONLY</p>
        </div>
    </div>
</header>

<div class="p-8 max-w-7xl mx-auto">
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Registered Parents -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 card-shadow">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">Registered Parents</p>
            <h4 class="text-4xl font-bold text-gray-900 mb-2">{{ $registeredParents }}</h4>
            <p class="text-xs text-gray-500 italic">Matched with study respondents</p>
        </div>

        <!-- Notifications Sent Today -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 card-shadow">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">Notifications Sent (Today)</p>
            <h4 class="text-4xl font-bold text-green-600">{{ $notificationsSentToday }}</h4>
        </div>

        <!-- SMS/Email Sync Status -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 card-shadow">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">SMS/Email Sync Status</p>
            <h4 class="text-4xl font-bold text-green-600 mb-2">{{ $syncStatus }}%</h4>
            <p class="text-xs text-green-600 font-bold uppercase">Verified Gateways</p>
        </div>
    </div>

    <!-- Guardian Database -->
    <div class="bg-white rounded-xl border border-gray-200 card-shadow overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-800 uppercase tracking-wider text-sm">Guardian Database</h3>
        </div>

        <div class="px-6 py-4 border-b border-gray-100">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                <input type="text" placeholder="Search by parent or child name..." 
                       class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-xs focus:ring-1 focus:ring-green-500 outline-none w-full bg-gray-50">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Parent / Guardian Profile</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Connected Student</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Contact Sync</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">System Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($parents as $parent)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-sm uppercase">
                                    {{ substr($parent->first_name, 0, 1) }}{{ substr($parent->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $parent->full_name }}</div>
                                    <div class="text-xs text-gray-500 italic">{{ $parent->relationship }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($parent->students->isNotEmpty())
                                @php
                                    $student = $parent->students->first();
                                @endphp
                                <div class="font-semibold text-gray-800">{{ $student->full_name }}</div>
                                <div class="text-xs text-gray-500 uppercase">Grade {{ $student->grade_level }} - {{ $student->section }}</div>
                            @else
                                <span class="text-gray-400 italic text-xs">No student connected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if($parent->email)
                                    <div class="w-6 h-6 rounded bg-green-100 flex items-center justify-center">
                                        <i class="fa-solid fa-envelope text-green-600 text-xs"></i>
                                    </div>
                                @endif
                                @if($parent->phone)
                                    <div class="w-6 h-6 rounded bg-green-100 flex items-center justify-center">
                                        <i class="fa-solid fa-message text-green-600 text-xs"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <button class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded text-xs font-bold uppercase transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-bell"></i>
                                Notify Parent
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 text-sm">
                            No parents registered yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($parents->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $parents->links() }}
        </div>
        @endif

        <!-- Notification Protocol Warning -->
        <div class="px-6 py-4 bg-amber-50 border-t border-amber-100">
            <div class="flex items-start gap-3">
                <div class="w-5 h-5 rounded-full bg-amber-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fa-solid fa-exclamation text-white text-xs"></i>
                </div>
                <p class="text-xs text-gray-600 italic leading-relaxed">
                    <span class="font-bold text-gray-800">Notification Protocol:</span> Messages sent to parents will remain generic and prompt a physical visit to the Discipline Office to ensure confidentiality and maintain the "supportive and trusting" organizational environment[cite: 185, 285].
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
