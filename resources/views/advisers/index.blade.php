@extends('layouts.app')

@section('content')
<!-- Header -->
<header class="bg-white border-b border-gray-200 px-8 py-5 flex justify-between items-center sticky top-0 z-40">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Personnel Management: Advisers</h2>
        <p class="text-xs text-gray-500 font-medium mt-0.5">Manage access and section assignments for BEU faculty [cite: 346]</p>
    </div>
    <div>
        <a href="{{ route('advisers.create') }}" class="bg-green-700 hover:bg-green-800 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-plus text-xs"></i> Register New Adviser
        </a>
    </div>
</header>

<div class="p-8 max-w-7xl mx-auto">
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Advisers -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 card-shadow">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">Total Advisers</p>
            <div class="flex items-baseline gap-2">
                <h4 class="text-4xl font-bold text-gray-900">{{ str_pad($totalAdvisers, 2, '0', STR_PAD_LEFT) }}</h4>
                <span class="text-xs text-gray-500">Active [cite: 379]</span>
            </div>
        </div>

        <!-- Sections Covered -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 card-shadow">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">Sections Covered</p>
            <div class="flex items-baseline gap-2">
                <h4 class="text-4xl font-bold text-gray-900">{{ str_pad($sectionsCovered, 2, '0', STR_PAD_LEFT) }}</h4>
                <span class="text-xs text-gray-500">JHS Dept [cite: 382]</span>
            </div>
        </div>

        <!-- Reports Logged -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 card-shadow">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">Reports Logged</p>
            <h4 class="text-4xl font-bold text-green-600">{{ $reportsLogged }}</h4>
        </div>
    </div>

    <!-- Advisers Table -->
    <div class="bg-white rounded-xl border border-gray-200 card-shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                <input type="text" placeholder="Search by name or employee ID..." 
                       class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-xs focus:ring-1 focus:ring-green-500 outline-none w-80 bg-gray-50">
            </div>
            <button class="px-4 py-2 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-50 flex items-center gap-2">
                <i class="fa-solid fa-download"></i>
                Export List
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Faculty Profile</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Employee ID</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Handling Section</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Registry Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Administrative Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($advisers as $adviser)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-sm uppercase">
                                    {{ substr($adviser->name, 0, 1) }}{{ substr(explode(' ', $adviser->name)[1] ?? '', 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $adviser->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $adviser->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 font-mono text-xs">{{ $adviser->employee_id }}</td>
                        <td class="px-6 py-4 text-gray-700">
                            @if($adviser->advisedStudents->isNotEmpty())
                                @php
                                    $student = $adviser->advisedStudents->first();
                                @endphp
                                Grade {{ $student->grade_level }} - {{ $student->section }}
                            @else
                                <span class="text-gray-400 italic">No section assigned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-green-50 text-green-700 border border-green-200 uppercase tracking-wide">
                                Verified
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('advisers.edit', $adviser) }}" class="text-blue-600 hover:text-blue-800 text-xs font-bold uppercase tracking-wide">
                                    Update Record [cite: 346]
                                </a>
                                <span class="text-gray-300">|</span>
                                <a href="{{ route('advisers.show', $adviser) }}" class="text-gray-600 hover:text-gray-800 text-xs font-bold uppercase tracking-wide">
                                    View Details
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">
                            No advisers registered yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($advisers->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $advisers->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
