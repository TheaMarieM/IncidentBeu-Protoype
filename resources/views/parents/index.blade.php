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
        <div class="px-6 py-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="font-bold text-gray-800 uppercase tracking-wider text-sm">Guardian Database</h3>
            
            <form action="{{ route('parents.index') }}" method="GET" class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                <!-- Grade Filter -->
                <div class="relative w-full md:w-32">
                    <select name="grade_level" onchange="this.form.submit()" class="w-full pl-3 pr-8 py-2 border border-gray-200 rounded-lg text-xs focus:ring-1 focus:ring-green-500 outline-none bg-gray-50 appearance-none cursor-pointer">
                        <option value="">All Grades</option>
                        @foreach(range(7, 12) as $grade)
                            <option value="{{ $grade }}" {{ request('grade_level') == $grade ? 'selected' : '' }}>Grade {{ $grade }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                        <i class="fa-solid fa-chevron-down text-[10px]"></i>
                    </div>
                </div>

                <!-- Section Filter -->
                <div class="relative w-full md:w-40">
                    <i class="fa-solid fa-users-rectangle absolute left-3 top-2.5 text-gray-400 text-xs pointer-events-none"></i>
                    <input type="text" name="section" value="{{ request('section') }}" placeholder="Filter Section..." 
                           onkeydown="if(event.key === 'Enter') this.form.submit()"
                           class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-xs focus:ring-1 focus:ring-green-500 outline-none w-full bg-gray-50">
                </div>

                <!-- Search -->
                <div class="relative w-full md:w-64">
                    <button type="submit" class="absolute left-0 top-0 bottom-0 pl-3 pr-2 text-gray-400 hover:text-green-600 transition-colors focus:outline-none" title="Click to Search">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </button>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." 
                           onkeydown="if(event.key === 'Enter') this.form.submit()"
                           class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-xs focus:ring-1 focus:ring-green-500 outline-none w-full bg-gray-50">
                    @if(request()->anyFilled(['search', 'grade_level', 'section']))
                        <a href="{{ route('parents.index') }}" class="absolute right-3 top-2.5 text-gray-400 hover:text-red-500" title="Reset Filters">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </a>
                    @endif
                </div>

                <noscript><button type="submit" class="hidden">Filter</button></noscript>
            </form>
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
                            <button onclick="openNotifyModal('{{ $parent->id }}', '{{ $parent->full_name }}')" 
                                class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded text-xs font-bold uppercase transition-colors flex items-center gap-2">
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

<!-- Notify Modal -->
<div id="notifyModal" class="fixed inset-0 bg-gray-900/50 hidden z-50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all scale-100">
        <form id="notifyForm" method="POST" action="">
            @csrf
            
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-amber-50">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                        <i class="fa-solid fa-bell"></i>
                    </span>
                    Notify <span id="modalParentName">Parent</span>
                </h3>
                <button type="button" onclick="closeNotifyModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Notification Subject</label>
                    <input type="text" name="subject" required placeholder="e.g. Guidance Office Meeting"
                        class="w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500 text-sm">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Message</label>
                    <textarea name="message" rows="4" required placeholder="Type your message here..."
                        class="w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500 text-sm"></textarea>
                </div>
                
                <div class="bg-blue-50 p-3 rounded text-xs text-blue-700 flex items-start gap-2">
                    <i class="fa-solid fa-circle-info mt-0.5"></i>
                    <p>This notification will be visible in the Parent Portal immediately.</p>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeNotifyModal()" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-amber-500 rounded-lg hover:bg-amber-600 shadow-sm">
                    Send Notification
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openNotifyModal(parentId, parentName) {
        const modal = document.getElementById('notifyModal');
        const form = document.getElementById('notifyForm');
        const nameSpan = document.getElementById('modalParentName');
        
        // Set Action URL
        form.action = `/parents/${parentId}/notify`;
        
        // Set Name
        nameSpan.textContent = parentName;
        
        // Show Modal
        modal.classList.remove('hidden');
    }

    function closeNotifyModal() {
        document.getElementById('notifyModal').classList.add('hidden');
    }
    
    // Close on outside click
    document.getElementById('notifyModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeNotifyModal();
        }
    });
</script>
@endsection
