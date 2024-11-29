<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules()
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        $birthDate = Carbon::parse($value);
                        if ($birthDate->isFuture()) {
                            $fail('Birth date cannot be in the future.');
                        }
                        if ($birthDate->age > 120) {
                            $fail('Please enter a valid birth date.');
                        }
                    }
                },
            ],
            'gender' => 'nullable|in:male,female,other',
            'contact_number' => [
                'nullable',
                'string',
                'max:15',
                'regex:/^([0-9\s\-\+\(\)]*)$/' // Allow numbers, spaces, hyphens, plus signs, and parentheses
            ],
            'home_address' => 'nullable|string|max:255',
        ];

        // Add guardian fields validation for child accounts
        if ($this->user()->account_type === 'child') {
            $rules['guardian_role'] = 'required|string|max:255';
            $rules['guardian_name'] = 'required|string|max:255';
        }

        // Add therapist-specific validation rules
        if ($this->user()->account_type === 'child') {
            $rules['specialization'] = 'nullable|string|max:255';
            $rules['availability'] = 'nullable|array';
            $rules['availability.*'] = 'string|max:255';
            $rules['teletherapist_link'] = [
                'nullable',
                'url',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                        $fail('Please enter a valid URL for the teletherapist link.');
                    }
                }
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.unique' => 'This email is already taken.',
            'contact_number.regex' => 'Please enter a valid phone number.',
            'guardian_role.required' => 'Guardian role is required for child accounts.',
            'guardian_name.required' => 'Guardian name is required for child accounts.',
            'teletherapist_link.url' => 'Please enter a valid URL for the teletherapist link.',
            'gender.in' => 'Please select a valid gender option.',
            'availability.array' => 'Availability must be a list of time slots.',
            'availability.*.string' => 'Each availability slot must be a valid string.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Trim whitespace from string inputs
        $this->merge([
            'first_name' => trim($this->first_name),
            'middle_name' => $this->middle_name ? trim($this->middle_name) : null,
            'last_name' => trim($this->last_name),
            'home_address' => $this->home_address ? trim($this->home_address) : null,
            'contact_number' => $this->contact_number ? trim($this->contact_number) : null,
        ]);

        // Format phone number if present
        if ($this->contact_number) {
            $this->merge([
                'contact_number' => preg_replace('/[^0-9+]/', '', $this->contact_number)
            ]);
        }
    }
}
