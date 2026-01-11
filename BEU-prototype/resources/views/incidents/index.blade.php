@extends('layouts.app')

@section('content')
<!-- Header -->
<header class="bg-white border-b border-gray-200 px-8 py-5 sticky top-0 z-40">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Incident Management Logs</h2>
        <p class="text-xs text-gray-500 font-medium mt-0.5">Record, track, and manage student behavioral cases</p>
    </div>
</header>

<div class="p-8 max-w-7xl mx-auto">
    
    <!-- New Incident Entry Form -->
    <div class="bg-white rounded-xl border border-gray-200 card-shadow mb-8 p-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">New Incident Entry</h3>
            <p class="text-xs text-gray-400 italic">Standardized Reporting Protocol (ISO 25001)</p>
        </div>

        <form action="{{ route('incidents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Student Name -->
                <div class="relative">
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Student Name</label>
                    <input type="text" id="student_search" placeholder="Search by name or ID..." 
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none" autocomplete="off">
                    <div id="student_results" class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg mt-1 hidden max-h-48 overflow-y-auto z-50"></div>
                    <input type="hidden" id="selected_student_id" name="students[]">
                    <div id="selected_students" class="mt-3 space-y-2"></div>
                </div>

                <!-- Violation Type -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Violation Type (Standardized)</label>
                    <select name="violation_category_id" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                        <option value="">Select violation...</option>
                        @foreach($violationCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Date -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Date</label>
                    <input type="date" name="incident_date" required value="{{ old('incident_date', now()->format('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                </div>

                <!-- Time -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Time</label>
                    <input type="time" name="incident_time" required value="{{ old('incident_time', now()->format('H:i')) }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Location of Incident</label>
                    <input type="text" name="location" placeholder="e.g., Canteen, Classroom 10A" required value="{{ old('location') }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Mandatory Violation Summary -->
                <div>
                    <label class="block text-[11px] font-bold text-red-500 uppercase tracking-wider mb-2">Mandatory Violation Summary</label>
                    <textarea name="description" rows="4" required
                              placeholder="Brief official summary for Principal's approval..."
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none resize-none">{{ old('description') }}</textarea>
                </div>

                <!-- Narrative Report (Optional) -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Narrative Report (Optional)</label>
                    <div class="border-2 border-dashed border-gray-200 rounded-lg p-6 text-center hover:border-green-500 transition-colors cursor-pointer" onclick="document.getElementById('narrative_file').click()">
                        <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-300 mb-2"></i>
                        <p class="text-xs text-gray-400 mb-1" id="file-name">Click to upload scanned narrative picture</p>
                        <input type="file" id="narrative_file" name="narrative_file" accept="image/*,.pdf" class="hidden" onchange="updateFileName(this)">
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2 italic">Upon submission, an automated summary report will be generated for the Principal[cite: 254].</p>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-8 py-3 rounded-lg text-sm font-bold transition-all shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-file-circle-check"></i>
                    Process Incident Log
                </button>
            </div>
        </form>
    </div>

    <!-- Master Incident Log -->
    <div class="bg-white rounded-xl border border-gray-200 card-shadow overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Master Incident Log</h3>
            <p class="text-xs text-gray-500 mt-1">Historical record of all documented cases</p>
        </div>

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                <input type="text" placeholder="Search by student or adviser..." 
                       class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-xs focus:ring-1 focus:ring-green-500 outline-none w-80 bg-gray-50">
            </div>
            <button class="px-4 py-2 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-50 flex items-center gap-2">
                <i class="fa-solid fa-file-pdf text-red-500"></i>
                Export PDF
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Case ID</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Incident Detail</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Parties Involved</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Reporting Authority</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Workflow Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($incidents as $incident)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500 text-xs font-mono">#INC-{{ now()->year }}-{{ str_pad($incident->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">{{ $incident->category->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $incident->incident_date->format('M d, Y') }} • Grade {{ $incident->students->first()->grade_level ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            @if($incident->students->isNotEmpty())
                                {{ $incident->students->first()->full_name }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-700">{{ $incident->reporter->name ?? 'N/A' }}</div>
                            <div class="text-[10px] text-gray-400 uppercase tracking-wide">{{ $incident->reporter->role->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($incident->status === 'pending_approval')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100 uppercase">
                                    <i class="fa-solid fa-circle-notch fa-spin mr-1.5"></i> Submitted for Review
                                </span>
                            @elseif($incident->status === 'approved')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-green-50 text-green-700 border border-green-100 uppercase">
                                    <i class="fa-solid fa-circle-check mr-1.5"></i> Done / Closed
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-gray-50 text-gray-700 border border-gray-100 uppercase">
                                    {{ $incident->status }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('incidents.show', $incident) }}" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded text-[10px] font-bold uppercase transition-colors">
                                    Manage
                                </a>
                                @if($incident->status === 'approved')
                                <button class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded text-[10px] font-bold uppercase transition-colors">
                                    Archive
                                </button>
                                @endif
                            </div>
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

        @if($incidents->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $incidents->links() }}
        </div>
        @endif
    </div>

</div>

<script>
function updateFileName(input) {
    const fileName = input.files[0]?.name;
    if (fileName) {
        document.getElementById('file-name').textContent = fileName;
    }
}

// Student search autocomplete
const searchInput = document.getElementById('student_search');
const resultsDiv = document.getElementById('student_results');
const selectedStudentsDiv = document.getElementById('selected_students');
let selectedStudents = {};

searchInput.addEventListener('input', async (e) => {
    const query = e.target.value.trim();
    
    if (query.length < 2) {
        resultsDiv.classList.add('hidden');
        return;
    }
    
    try {
        const response = await fetch(`{{ route('incidents.search-students') }}?q=${encodeURIComponent(query)}`);
        const students = await response.json();
        
        resultsDiv.innerHTML = '';
        
        if (students.length === 0) {
            resultsDiv.innerHTML = '<div class="px-4 py-2 text-gray-500 text-sm">No students found</div>';
            resultsDiv.classList.remove('hidden');
            return;
        }
        
        students.forEach(student => {
            const div = document.createElement('div');
            div.className = 'px-4 py-2.5 hover:bg-green-50 cursor-pointer border-b border-gray-100 text-sm transition-colors';
            div.innerHTML = `
                <div class="font-medium text-gray-900">${student.text}</div>
                <div class="text-[11px] text-gray-500">Grade ${student.grade_level} • ${student.section}</div>
            `;
            div.addEventListener('click', () => selectStudent(student));
            resultsDiv.appendChild(div);
        });
        
        resultsDiv.classList.remove('hidden');
    } catch (error) {
        console.error('Search error:', error);
    }
});

function selectStudent(student) {
    if (selectedStudents[student.id]) {
        return; // Already selected
    }
    
    selectedStudents[student.id] = student;
    document.getElementById('student_search').value = '';
    document.getElementById('student_results').classList.add('hidden');
    
    renderSelectedStudents();
}

function removeStudent(studentId) {
    delete selectedStudents[studentId];
    renderSelectedStudents();
}

function renderSelectedStudents() {
    selectedStudentsDiv.innerHTML = '';
    
    Object.entries(selectedStudents).forEach(([id, student]) => {
        const div = document.createElement('div');
        div.className = 'flex items-center justify-between bg-green-50 border border-green-200 rounded-lg px-4 py-2.5';
        div.innerHTML = `
            <div>
                <div class="text-sm font-medium text-gray-900">${student.text}</div>
                <div class="text-xs text-gray-500">Grade ${student.grade_level} • ${student.section}</div>
            </div>
            <button type="button" class="text-red-600 hover:text-red-700" onclick="removeStudent(${id})">
                <i class="fa-solid fa-times"></i>
            </button>
            <input type="hidden" name="students[]" value="${id}">
        `;
        selectedStudentsDiv.appendChild(div);
    });
}

// Close results when clicking outside
document.addEventListener('click', (e) => {
    if (!e.target.closest('#student_search') && !e.target.closest('#student_results')) {
        document.getElementById('student_results').classList.add('hidden');
    }
});
</script>
@endsection
