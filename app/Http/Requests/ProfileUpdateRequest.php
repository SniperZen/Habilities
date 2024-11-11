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
        return [
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
            'gender' => 'nullable|in:male,female,other', // Adjust based on your requirements
            'contact_number' => 'nullable|string|max:15', // Adjust max length as necessary
            'specialization' => 'nullable|string|max:255', // Only for therapists
            'home_address' => 'nullable|string|max:255',
            'availability' => 'nullable|array', // Allow availability to be an array
            'availability.*' => 'string|max:255', // Ensure each element is a string
            'teletherapist_link' => 'nullable|url',

        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.unique' => 'This email is already taken.',
            // Add custom messages for other fields if needed
        ];
    }
}
