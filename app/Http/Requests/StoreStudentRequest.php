<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && in_array(
            Auth::user()->role?->name,
            ['discipline_coordinator', 'principal', 'assistant_principal']
        );
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|string|max:50|unique:students,student_id|regex:/^[A-Z0-9\-]+$/',
            'first_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\.]+$/',
            'middle_name' => 'nullable|string|max:255|regex:/^[a-zA-Z\s\-\.]+$/',
            'last_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\.]+$/',
            'date_of_birth' => 'required|date|before:today|after:' . now()->subYears(25)->format('Y-m-d'),
            'gender' => 'required|in:male,female',
            'grade_level' => 'required|integer|between:7,12',
            'section' => 'required|string|max:100|regex:/^[a-zA-Z0-9\s\-]+$/',
            'adviser_id' => 'required|integer|exists:users,id',
            'address' => 'nullable|string|max:1000',
            'emergency_contact' => 'nullable|string|max:255|regex:/^[0-9\+\-\(\)\s]+$/',
            'medical_conditions' => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.regex' => 'Student ID must contain only uppercase letters, numbers, and hyphens.',
            'first_name.regex' => 'First name contains invalid characters.',
            'last_name.regex' => 'Last name contains invalid characters.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
            'date_of_birth.after' => 'Student must be under 25 years old.',
            'grade_level.between' => 'Grade level must be between 7 and 12.',
            'emergency_contact.regex' => 'Emergency contact must be a valid phone number.',
        ];
    }

    protected function prepareForValidation()
    {
        // Sanitize inputs
        $this->merge([
            'first_name' => strip_tags($this->first_name ?? ''),
            'middle_name' => strip_tags($this->middle_name ?? ''),
            'last_name' => strip_tags($this->last_name ?? ''),
            'address' => strip_tags($this->address ?? ''),
            'medical_conditions' => strip_tags($this->medical_conditions ?? ''),
        ]);
    }
}
