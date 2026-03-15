<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Models\Admin\Official;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Get the user ID from route if it's an update
        $userId = $this->route('user') ?? $this->route('ua') ?? $this->route('id');
        $validRoles = Role::query()->pluck('name')->all();
        $validOfficials = Official::query()->pluck('id')->all();
        
        $rules = [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                $userId 
                    ? Rule::unique('users')->ignore($userId)
                    : Rule::unique('users')
            ],
            'role' => [
                'required',
                Rule::in($validRoles),
            ],
        ];

        // Add password only for creation
        if (!$userId) {
            $rules['password'] = [
                'required',
                'string',
                'min:8',
                'confirmed',
            ];
            $rules['password_confirmation'] = 'required|string';
        } else {
            // Password is optional for updates
            $rules['password'] = [
                'sometimes',
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ];
            $rules['password_confirmation'] = 'sometimes|nullable|string';
        }

        if($this->input('role') === 'committee_head') {
            $rules['official_id'] = [
                'required',
                Rule::in($validOfficials),
            ];
        }
        
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Please provide a name.',
            'email.required' => 'An email address is required.',
            'email.unique' => 'This email is already registered.',
            'role.required' => 'Please select a role.',
            'role.in' => 'The selected role is invalid.',
            'password.required' => 'A password is required for new users.',
            'password.min' => 'Password must be at least 8 characters.',
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
