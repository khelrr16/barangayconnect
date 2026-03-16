<?php

namespace App\Http\Requests;

use App\Models\Household;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHouseholdRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'block' => 'required|string|max:50',
            'lot' => 'required|string|max:50',
            'unit' => 'nullable|string|max:50',
            'street' => 'required|string|max:255',
            'subdivision' => 'required|string|max:255',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check for duplicate address
            $existingHousehold = Household::where('subdivision', $this->subdivision)
                ->where('street', $this->street)
                ->where('block', $this->block)
                ->where('lot', $this->lot)
                ->where('unit', $this->unit)
                ->where('id', '!=', $this->route('household')) // Get ID from route
                ->first();

            if ($existingHousehold) {
                $validator->errors()->add('address', "The same exact address already exist. {$existingHousehold->household_no}");
            }
        });
    }

    public function messages()
    {
        return [
            'block.required' => 'Block number is required.',
            'lot.required' => 'Lot number is required.',
            'street.required' => 'Street is required.',
            'subdivision.required' => 'Subdivision is required.',
        ];
    }
}
