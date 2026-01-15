@extends('layouts.adviser')

@section('content')
@php
    $userName = auth()->user()->name ?? 'Adviser';
@endphp

<header class="bg-white border-b border-gray-200 px-8 py-4 flex flex-wrap gap-4 justify-between items-center sticky top-0 z-30 shadow-sm">
    <div>
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('adviser.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Register Student</h1>
        </div>
        <p class="text-sm text-gray-500 mt-1">Fill in the student's basic information and family details.</p>
    </div>
    <div class="flex items-center gap-4">
        <div class="h-10 w-px bg-gray-300"></div>
        <div class="flex items-center gap-3">
            <p class="text-sm font-bold text-gray-900">Adviser</p>
            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                <i class="fa-solid fa-user"></i>
            </div>
        </div>
    </div>
</header>

<div class="p-8 space-y-8">
    <section class="max-w-4xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-3xl overflow-hidden card-shadow">
            <div class="px-7 py-6 border-b border-gray-100">
                <p class="text-xs uppercase tracking-[0.2em] text-gray-400 font-semibold">Student Registration Form</p>
                <h2 class="text-2xl font-black text-gray-900 mt-1">Basic Information & Family Details</h2>
                <p class="text-xs text-gray-500 mt-1">Please ensure all required fields are accurately filled.</p>
            </div>

            <form action="{{ route('adviser.students.store') }}" method="POST" class="p-8 space-y-8">
                @csrf

                <!-- Student Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">Student Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                            @error('first_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                            @error('middle_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                            @error('last_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Student ID <span class="text-red-500">*</span></label>
                            <input type="text" name="student_id" value="{{ old('student_id') }}" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                            @error('student_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">Address Information</h3>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Residential Address <span class="text-red-500">*</span></label>
                        <textarea name="residential_address" rows="3" required 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">{{ old('residential_address') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Complete permanent home address</p>
                        @error('residential_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Apartment / Boarding House Address</label>
                        <textarea name="boarding_address" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">{{ old('boarding_address') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Optional - if living away from home</p>
                        @error('boarding_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Primary Guardian Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">Primary Guardian</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Guardian Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                            @error('guardian_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Number <span class="text-red-500">*</span></label>
                            <input type="text" name="guardian_contact" value="{{ old('guardian_contact') }}" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                            @error('guardian_contact')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Family Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">Family Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mother's Full Name</label>
                            <input type="text" name="mother_name" value="{{ old('mother_name') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                            @error('mother_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Father's Full Name</label>
                            <input type="text" name="father_name" value="{{ old('father_name') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                            @error('father_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('adviser.dashboard') }}" class="px-6 py-3 text-sm font-semibold border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 text-sm font-semibold rounded-lg bg-green-700 text-white hover:bg-green-800 transition-colors inline-flex items-center gap-2">
                        <i class="fa-solid fa-check"></i> Register Student
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>

@endsection
