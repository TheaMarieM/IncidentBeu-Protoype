@extends('layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Class Adviser</h1>
            <p class="text-sm text-gray-500">Update adviser profile and section assignment.</p>
        </div>
        <a href="{{ route('advisers.index') }}" class="text-gray-600 hover:text-gray-900 font-medium text-sm flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <form action="{{ route('advisers.update', $adviser) }}" method="POST" class="divide-y divide-gray-100">
            @csrf
            @method('PUT')

            <!-- Personal Information -->
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 text-sm">
                        <i class="fa-regular fa-user"></i>
                    </span>
                    Personal Information <span class="text-xs font-normal text-gray-500 ml-2">(Read Only)</span>
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $adviser->name) }}" 
                            class="block w-full mt-1 rounded-md shadow-sm border-gray-300 bg-gray-100 text-gray-600 cursor-not-allowed focus:border-gray-300 focus:ring-0" readonly>
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="employee_id" class="block text-sm font-semibold text-gray-700 mb-2">Employee ID</label>
                        <input type="text" name="employee_id" id="employee_id" value="{{ old('employee_id', $adviser->employee_id) }}" 
                            class="block w-full mt-1 rounded-md shadow-sm border-gray-300 bg-gray-100 text-gray-600 cursor-not-allowed focus:border-gray-300 focus:ring-0" readonly>
                        @error('employee_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $adviser->email) }}" 
                            class="block w-full mt-1 rounded-md shadow-sm border-gray-300 bg-gray-100 text-gray-600 cursor-not-allowed focus:border-gray-300 focus:ring-0" readonly>
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $adviser->phone) }}" 
                            class="block w-full mt-1 rounded-md shadow-sm border-gray-300 bg-gray-100 text-gray-600 cursor-not-allowed focus:border-gray-300 focus:ring-0" readonly>
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Section Assignment -->
            <div class="p-6 bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-sm">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </span>
                    Class Section Assignment
                </h3>
                <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 mb-4 text-xs text-blue-800 flex items-start gap-2">
                    <i class="fa-solid fa-circle-info mt-0.5"></i>
                    <p>Updating these fields will update the Grade Level and Section for <strong>all students</strong> currently assigned to this adviser.</p>
                </div>

                @php
                    $currentStudent = $adviser->advisedStudents->first();
                    $currentGrade = $currentStudent ? $currentStudent->grade_level : '';
                    $currentSection = $currentStudent ? $currentStudent->section : '';
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="grade_level" class="block text-sm font-semibold text-gray-700 mb-2">Grade Level</label>
                        <select name="grade_level" id="grade_level" class="block w-full mt-1 rounded-md shadow-sm border-gray-300 focus:border-green-500 focus:ring-green-500">
                            <option value="">Select Grade Level</option>
                            @for($i = 7; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('grade_level', $currentGrade) == $i ? 'selected' : '' }}>Grade {{ $i }}</option>
                            @endfor
                        </select>
                        @error('grade_level')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="section" class="block text-sm font-semibold text-gray-700 mb-2">Section Name</label>
                        <input type="text" name="section" id="section" value="{{ old('section', $currentSection) }}" 
                            placeholder="e.g. St. Matthew"
                            class="block w-full mt-1 rounded-md shadow-sm border-gray-300 focus:border-green-500 focus:ring-green-500">
                        @error('section')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                <a href="{{ route('advisers.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-green-700 rounded-lg hover:bg-green-800 shadow-sm">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
