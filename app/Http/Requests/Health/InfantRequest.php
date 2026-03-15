<?php

namespace App\Http\Requests\Health;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InfantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'family_serial_number' => [
                'required',
                'string',
                'max:255',
                $this->getUniqueRule(),
            ],
            'name' => 'required|string|max:255',
            'sex' => 'required|string|in:Male,Female',
            'birthday' => 'required|date|before:today',
            'mother_name' => 'required|string|max:255',
            'cpab' => 'required|integer|in:1,2', // Assuming 1 or 2 values
            'address_type' => 'required|in:san_lorenzo,non_san_lorenzo',
        ];

        if ($this->input('address_type') === 'san_lorenzo') {
            $rules = array_merge($rules, [
                'block' => 'required|string|max:50',
                'lot' => 'required|string|max:50',
                'unit' => 'nullable|string|max:50',
                'street' => 'required|string|max:255',
                'subdivision' => 'required|string|max:255',
            ]);
        } else {
            $rules = array_merge($rules, [
                'house_number' => 'required|string|max:50',
                'street' => 'nullable|string|max:255',
                'subdivision' => 'nullable|string|max:255',
                'barangay' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'province' => 'required|string|max:255',
            ]);
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        // Clean up inputs before validation
        $this->merge([
            'name' => trim($this->name ?? ''),
            'mother_name' => trim($this->mother_name ?? ''),
            'family_serial_number' => strtoupper(trim($this->family_serial_number ?? '')),
        ]);
    }

    protected function getUniqueRule()
    {
        $rule = Rule::unique('infants', 'family_serial_number');
        
        // If this is an update, ignore the current record
        if ($this->route('infant')) {
            $rule->ignore($this->route('infant')->id);
        }
        
        // Add soft delete scope if you use soft deletes
        // $rule->whereNull('deleted_at');
        
        return $rule;
    }
}
