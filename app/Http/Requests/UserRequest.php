<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Get the user ID from route if it's an update
        $userId = $this->route('user') ?? $this->route('id');
        
        $rules = [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                $userId 
                    ? Rule::unique('users')->ignore($userId)
                    : Rule::unique('users')
            ]
        ];
        
        // Add password only for creation
        if (!$userId) {
            $rules['password'] = [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/' // At least one uppercase, one lowercase, one number
            ];
            $rules['password_confirmation'] = 'required|string';
        } else {
            // Password is optional for updates
            $rules['password'] = [
                'sometimes',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/'
            ];
            $rules['password_confirmation'] = 'sometimes|string';
        }
        
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Please provide a name.',
            'name.regex' => 'The name may only contain letters and spaces.',
            'email.required' => 'An email address is required.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'A password is required for new users.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
            'password.confirmed' => 'Password confirmation does not match.'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => $this->has('name') ? trim(strip_tags($this->name)) : null,
            'email' => $this->has('email') ? strtolower(trim($this->email)) : null,
        ]);
    }
}
