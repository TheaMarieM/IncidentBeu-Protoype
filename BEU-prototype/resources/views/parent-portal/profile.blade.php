@extends('layouts.parent')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Profile Header Card -->
        <div class="bg-white rounded-lg card-shadow p-8 mb-6 border-l-4 border-green-600">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ $parent->first_name }} {{ $parent->middle_name ? $parent->middle_name[0] . '. ' : '' }}{{ $parent->last_name }}
                    </h1>
                    <p class="text-gray-600 mt-1">
                        <i class="fas fa-user-tie text-green-600 mr-2"></i>{{ ucfirst($parent->relationship) }}
                    </p>
                </div>
                <div class="text-5xl text-green-200">
                    <i class="fas fa-user-circle"></i>
                </div>
            </div>

            <!-- Quick Info Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-xs text-gray-600 font-semibold mb-1">EMAIL</p>
                    <p class="text-sm text-gray-900">{{ $parent->email }}</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-xs text-gray-600 font-semibold mb-1">PHONE</p>
                    <p class="text-sm text-gray-900">{{ $parent->phone ?? 'Not provided' }}</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-xs text-gray-600 font-semibold mb-1">STATUS</p>
                    <p class="text-sm">
                        <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                            {{ ucfirst($parent->status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Contact Information -->
            <div class="bg-white rounded-lg card-shadow p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">
                    <i class="fas fa-phone-alt text-green-600 mr-2"></i>Contact Information
                </h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">PRIMARY PHONE</label>
                        <p class="text-gray-900">{{ $parent->phone ?? 'Not provided' }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">ALTERNATE PHONE</label>
                        <p class="text-gray-900">{{ $parent->alternate_phone ?? 'Not provided' }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">ADDRESS</label>
                        <p class="text-gray-900">{{ $parent->address ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Linked Children -->
            <div class="bg-white rounded-lg card-shadow p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">
                    <i class="fas fa-children text-green-600 mr-2"></i>Linked Children
                </h2>

                @if ($parent->students->count() === 0)
                    <p class="text-gray-500 text-center py-8">No children linked to this account.</p>
                @else
                    <div class="space-y-3">
                        @foreach ($parent->students as $child)
                            <div class="bg-green-50 rounded-lg p-4 border border-green-200 hover:bg-green-100 transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            {{ $child->first_name }} {{ $child->middle_name ? $child->middle_name[0] . '. ' : '' }}{{ $child->last_name }}
                                        </p>
                                        <p class="text-xs text-gray-600 mt-1">
                                            <i class="fas fa-id-card mr-1"></i>{{ $child->student_id }} • Grade {{ $child->grade_level }}
                                        </p>
                                    </div>
                                    <a href="{{ route('parent.view-child', $child->student_id) }}"
                                        class="text-green-600 hover:text-green-700 font-semibold text-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Information Notice -->
        <div class="bg-blue-50 border-l-4 border-blue-600 rounded-lg p-6">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 mr-3 mt-1 text-lg"></i>
                <div>
                    <h3 class="font-semibold text-blue-900 mb-1">Profile Updates</h3>
                    <p class="text-blue-800 text-sm">For any corrections to your personal information or to link additional children, please contact the school administration office.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
