@extends('layouts.app')

@section('content')
<!-- Header -->
<header class="bg-white border-b border-gray-200 px-8 py-5 sticky top-0 z-40">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Incident Details & Management</h2>
            <p class="text-xs text-gray-500 font-medium mt-0.5">
                <span class="font-mono font-bold">{{ $incident->incident_number }}</span> 
                • {{ $incident->incident_date->format('M d, Y h:i A') }}
            </p>
        </div>
        <a href="{{ route('incidents.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-medium text-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Logs
        </a>
    </div>
</header>

<div class="p-8 max-w-7xl mx-auto">
    <!-- Status Alert -->
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-800 font-semibold mb-2">
                <i class="fas fa-exclamation-circle"></i> Error:
            </p>
            <ul class="text-red-700 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-green-800 font-semibold">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </p>
        </div>
    @endif

    <!-- Status Badge -->
    <div class="mb-8">
        @if($incident->status === 'reported')
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-blue-50 text-blue-700 border border-blue-200 uppercase">
                <i class="fa-solid fa-clipboard mr-2"></i> Reported
            </span>
        @elseif($incident->status === 'under_review')
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-purple-50 text-purple-700 border border-purple-200 uppercase">
                <i class="fa-solid fa-magnifying-glass mr-2"></i> Under Review
            </span>
        @elseif($incident->status === 'pending_approval')
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-orange-50 text-orange-700 border border-orange-200 uppercase">
                <i class="fa-solid fa-hourglass-end mr-2"></i> Pending Approval
            </span>
        @elseif($incident->status === 'approved')
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-50 text-green-700 border border-green-200 uppercase">
                <i class="fa-solid fa-circle-check mr-2"></i> Approved
            </span>
        @elseif($incident->status === 'closed')
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gray-50 text-gray-700 border border-gray-200 uppercase">
                <i class="fa-solid fa-folder-closed mr-2"></i> Closed
            </span>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Incident Overview -->
            <div class="bg-white rounded-xl border border-gray-200 card-shadow p-8">
                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fa-solid fa-info-circle text-green-600 mr-3"></i> Incident Overview
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Date & Time</label>
                        <p class="text-sm font-semibold text-gray-800 mt-1">
                            {{ $incident->incident_date->format('F d, Y') }}<br>
                            <span class="text-xs text-gray-600">{{ $incident->incident_date->format('h:i A') }}</span>
                        </p>
                    </div>
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Location</label>
                        <p class="text-sm font-semibold text-gray-800 mt-1">{{ $incident->location }}</p>
                    </div>
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Reported By</label>
                        <p class="text-sm font-semibold text-gray-800 mt-1">
                            {{ $incident->reporter->name ?? 'N/A' }}<br>
                            <span class="text-xs text-gray-600 uppercase">{{ $incident->reporter->role->name ?? 'N/A' }}</span>
                        </p>
                    </div>
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Violation Category</label>
                        <p class="text-sm font-semibold text-gray-800 mt-1">
                            @if($incident->category)
                                {{ $incident->category->name }}
                            @else
                                <span class="text-gray-400 italic">Not set</span>
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Description</label>
                    <p class="text-sm text-gray-800 mt-2 leading-relaxed">{{ $incident->description }}</p>
                </div>
            </div>

            <!-- Students Involved -->
            <div class="bg-white rounded-xl border border-gray-200 card-shadow p-8">
                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fa-solid fa-users text-green-600 mr-3"></i> Students Involved
                </h3>
                
                <div class="space-y-4">
                    @forelse($incident->students as $student)
                    <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $student->full_name }}</h4>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    <span class="font-mono font-semibold">{{ $student->student_id }}</span> 
                                    • Grade {{ $student->grade_level }} - {{ $student->section }}
                                </p>
                            </div>
                            <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                Involved
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-100">
                            <div>
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Offense Count</label>
                                <p class="text-sm font-semibold text-gray-800 mt-1">{{ $student->pivot->offense_count ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Sanction</label>
                                <p class="text-sm font-semibold text-gray-800 mt-1">
                                    @if($student->pivot->sanction_id)
                                        {{ \App\Models\Sanction::find($student->pivot->sanction_id)->name ?? 'N/A' }}
                                    @else
                                        <span class="text-gray-400 italic">Pending</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if($student->pivot->narrative_report)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Narrative Report</label>
                            <p class="text-sm text-gray-700 mt-2">{{ $student->pivot->narrative_report }}</p>
                        </div>
                        @endif
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm text-center py-6">No students assigned to this incident.</p>
                    @endforelse
                </div>
            </div>

            <!-- Approvals & Notifications -->
            <div class="bg-white rounded-xl border border-gray-200 card-shadow p-8">
                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fa-solid fa-check-double text-green-600 mr-3"></i> Approvals & Notifications
                </h3>
                
                <div class="space-y-4">
                    @forelse($incident->approvals as $approval)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $approval->approver->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $approval->approver->role->name ?? 'N/A' }}</p>
                            </div>
                            @if($approval->status === 'approved')
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">Approved</span>
                            @elseif($approval->status === 'rejected')
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-semibold">Rejected</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs font-semibold">Pending</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-2">{{ $approval->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm text-center py-6">No approval records yet.</p>
                    @endforelse
                </div>

                @if($incident->notifications->isNotEmpty())
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h4 class="font-semibold text-gray-800 mb-4">Parent Notifications</h4>
                    <div class="space-y-2">
                        @foreach($incident->notifications as $notification)
                        <div class="text-sm bg-blue-50 border border-blue-200 rounded px-3 py-2">
                            <p class="font-medium text-blue-900">{{ $notification->status }}</p>
                            <p class="text-xs text-blue-700 mt-0.5">{{ $notification->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl border border-gray-200 card-shadow p-8">
                <h3 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider">Actions</h3>
                
                <div class="space-y-3">
                    @if($incident->status !== 'approved' && $incident->status !== 'closed')
                    <button onclick="document.getElementById('edit-incident-form').style.display = 'block'" 
                            class="w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium text-sm transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-pen-to-square"></i> Edit Incident
                    </button>
                    @endif
                    
                    @if($incident->status === 'under_review' || $incident->status === 'reported')
                    <form action="{{ route('incidents.approve', $incident) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" onclick="return confirm('Submit for principal approval?')"
                                class="w-full px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium text-sm transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-circle-check"></i> Submit for Approval
                        </button>
                    </form>
                    @endif

                    @if($incident->status === 'pending_approval')
                    <form action="{{ route('incidents.reject', $incident) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" onclick="return confirm('Reject this incident?')"
                                class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium text-sm transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-times-circle"></i> Return for Revision
                        </button>
                    </form>
                    @endif

                    @if($incident->status === 'approved')
                    <form action="{{ route('incidents.destroy', $incident) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Archive this incident?')"
                                class="w-full px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium text-sm transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-folder-closed"></i> Archive
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('incidents.edit', $incident) }}" 
                       class="w-full px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg font-medium text-sm transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-sliders"></i> Full Edit
                    </a>
                </div>
            </div>

            <!-- Incident Meta -->
            <div class="bg-white rounded-xl border border-gray-200 card-shadow p-8">
                <h3 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider">Details</h3>
                
                <div class="space-y-4 text-sm">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Incident ID</label>
                        <p class="font-mono text-gray-800 mt-1">{{ $incident->incident_number }}</p>
                    </div>
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Created</label>
                        <p class="text-gray-800 mt-1">{{ $incident->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Last Updated</label>
                        <p class="text-gray-800 mt-1">{{ $incident->updated_at->format('M d, Y h:i A') }}</p>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Students Involved</label>
                        <p class="text-gray-800 mt-1 font-bold">{{ $incident->students->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Related Links -->
            <div class="bg-white rounded-xl border border-gray-200 card-shadow p-8">
                <h3 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider">Navigate</h3>
                
                <div class="space-y-2">
                    <a href="{{ route('incidents.index') }}" class="block text-sm text-green-600 hover:text-green-700 font-medium">
                        <i class="fa-solid fa-list mr-2"></i> All Incidents
                    </a>
                    <a href="{{ route('dashboard') }}" class="block text-sm text-green-600 hover:text-green-700 font-medium">
                        <i class="fa-solid fa-chart-line mr-2"></i> Dashboard
                    </a>
                    <a href="{{ route('students.index') }}" class="block text-sm text-green-600 hover:text-green-700 font-medium">
                        <i class="fa-solid fa-graduation-cap mr-2"></i> Student Registry
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="edit-incident-form" style="display: none;" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-8 max-h-96 overflow-y-auto">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Edit</h3>
        
        <form action="{{ route('incidents.update', $incident) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                    <option value="reported" {{ $incident->status === 'reported' ? 'selected' : '' }}>Reported</option>
                    <option value="under_review" {{ $incident->status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                    <option value="pending_approval" {{ $incident->status === 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="approved" {{ $incident->status === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="closed" {{ $incident->status === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Location</label>
                <input type="text" name="location" value="{{ $incident->location }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium text-sm transition">
                    Save Changes
                </button>
                <button type="button" onclick="document.getElementById('edit-incident-form').style.display = 'none'" class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium text-sm transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
