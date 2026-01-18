<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreParentRequest extends FormRequest
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
            'first_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\.]+$/',
            'middle_name' => 'nullable|string|max:255|regex:/^[a-zA-Z\s\-\.]+$/',
            'last_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\.]+$/',
            'relationship' => 'required|string|max:255|in:father,mother,guardian,grandfather,grandmother,uncle,aunt,sibling',
            'email' => 'nullable|email:rfc,dns|max:255|unique:parents,email',
            'phone' => 'required|string|max:20|regex:/^[0-9\+\-\(\)\s]+$/',
            'alternate_phone' => 'nullable|string|max:20|regex:/^[0-9\+\-\(\)\s]+$/',
            'address' => 'nullable|string|max:1000',
            'occupation' => 'nullable|string|max:255',
            'students' => 'nullable|array|max:20',
            'students.*' => 'integer|exists:students,id',
            'primary_contact' => 'nullable|array|max:20',
            'primary_contact.*' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.regex' => 'First name contains invalid characters.',
            'last_name.regex' => 'Last name contains invalid characters.',
            'relationship.in' => 'Please select a valid relationship.',
            'email.email' => 'Please provide a valid email address.',
            'phone.regex' => 'Phone number format is invalid.',
            'students.max' => 'Maximum 20 students can be linked to one parent.',
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
            'occupation' => strip_tags($this->occupation ?? ''),
        ]);
    }
}
