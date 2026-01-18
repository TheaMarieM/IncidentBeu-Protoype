@extends('layouts.app')

@section('content')
<div class="h-full bg-slate-50 overflow-y-auto">
    <!-- Header -->
    <header class="bg-gradient-to-r from-green-800 to-green-700 border-b border-green-900 px-8 py-5 shadow-lg">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-black text-white">Log New Incident</h2>
                <p class="text-xs text-green-100 font-medium mt-1">Record a behavioral violation</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-white/80 hover:text-white text-sm font-semibold flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </header>

    <div class="p-8 max-w-5xl mx-auto pb-20">
        <form action="{{ route('incidents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Incident Details Card -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <h3 class="font-bold text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-green-600"></i> Incident Details
                    </h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date & Time -->
                    <div>
                        <x-input-label for="incident_date" value="Date & Time" />
                        <input type="datetime-local" id="incident_date" name="incident_date" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500" required value="{{ old('incident_date') }}">
                        <x-input-error :messages="$errors->get('incident_date')" class="mt-2" />
                    </div>

                    <!-- Location -->
                    <div>
                        <x-input-label for="location" value="Location" />
                        <x-text-input id="location" class="block w-full mt-1" type="text" name="location" :value="old('location')" required placeholder="e.g. Canteen, Hallway, Classroom A-101" />
                        <x-input-error :messages="$errors->get('location')" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <x-input-label for="description" value="Incident Description" />
                        <textarea id="description" name="description" rows="3" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Provide a detailed description of the incident..." required>{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <!-- Violation Category -->
                    <div>
                        <x-input-label for="violation_category_id" value="Violation Category" />
                        <select id="violation_category_id" name="violation_category_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('violation_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->severity_level }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('violation_category_id')" class="mt-2" />
                    </div>

                    <!-- Violation Clause (Can be populated via JS dependent on Category, or just listed) -->
                    <div>
                        <x-input-label for="violation_clause_id" value="Specific Clause (Optional)" />
                        <!-- Simplified for prototype: Assuming categories might load clauses via AJAX or just leave optional -->
                        <select id="violation_clause_id" name="violation_clause_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 bg-gray-50 cursor-not-allowed" disabled>
                            <option value="">Select Category First</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Involved Students Section -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden" x-data="studentManager()">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="font-bold text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-users-viewfinder text-green-600"></i> Involved Students
                    </h3>
                    <button type="button" @click="addStudent()" class="text-xs bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg font-bold transition-colors">
                        <i class="fa-solid fa-plus mr-1"></i> Add Student
                    </button>
                </div>
                
                <div class="p-6 space-y-4">
                    <template x-for="(student, index) in students" :key="student.id">
                        <div class="p-4 border border-slate-200 rounded-xl bg-slate-50 relative group">
                            <button type="button" @click="removeStudent(index)" class="absolute top-2 right-2 text-slate-400 hover:text-red-500 transition-colors" x-show="students.length > 1">
                                <i class="fa-solid fa-trash"></i>
                            </button>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Student Selection -->
                                <div class="md:col-span-2">
                                    <label class="block font-medium text-xs text-gray-700 mb-1">Select Student</label>
                                    <select :name="'students[' + index + ']'" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-sm" required>
                                        <option value="">Choose a student...</option>
                                        @foreach($students as $s)
                                            <option value="{{ $s->id }}">{{ $s->last_name }}, {{ $s->first_name }} ({{ $s->grade_level }}-{{ $s->section }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Narrative Report -->
                                <div>
                                    <label class="block font-medium text-xs text-gray-700 mb-1">Narrative Report</label>
                                    <textarea :name="'narrative_reports[' + index + ']'" rows="3" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-sm" placeholder="Specific involvement details..."></textarea>
                                </div>

                                <!-- Evidence Upload -->
                                <div>
                                    <label class="block font-medium text-xs text-gray-700 mb-1">Evidence / File</label>
                                    <input type="file" :name="'narrative_files[' + index + ']'" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100"/>
                                    <p class="text-[10px] text-gray-500 mt-1">PDF, JPG, PNG (Max 5MB)</p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Submit Actions -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('dashboard') }}" class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-bold text-sm hover:bg-gray-50 transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-green-700 hover:bg-green-800 text-white font-bold text-sm shadow-lg hover:shadow-xl transition-all">Submit Incident Report</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('studentManager', () => ({
            students: [{ id: Date.now() }],
            
            addStudent() {
                this.students.push({ id: Date.now() });
            },
            
            removeStudent(index) {
                this.students.splice(index, 1);
            }
        }))
    })
</script>
@endsection
