<?php

namespace App\Http\Requests;

use App\Models\Household;
use App\Models\Resident;
use Illuminate\Foundation\Http\FormRequest;

class StoreResidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            //Personal Info
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'extension_name' => 'nullable|string|max:50',
            'sex' => 'required|in:Male,Female',
            'birthday' => 'required|date',
            'civil_status' => 'required|string|max:50',
            'citizenship' => 'required|string|max:255',
            'birthplace' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'registered_voter' => 'required|string|max:50',

            //Residence Info
            'subdivision' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'block' => 'required|numeric|max:50',
            'lot' => 'required|numeric|max:50',
            'unit' => 'nullable|numeric|max:50',
            'role' => 'required|string|max:255',
            'residence_since' => 'required|integer',
            'ownership' => 'required|string|max:255',

            //Socio-Economic
            'educational_attainment' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'employment_status' => 'required|string|max:255',
            'monthly_income' => 'required|string|max:255',
            'religion' => 'required|string|max:255',

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $household = Household::where([
                'subdivision' => $this->subdivision,
                'street' => $this->street,
                'block' => $this->block,
                'lot' => $this->lot,
                'unit' => $this->unit,
            ])->first();
            
            if ($household) {
                $existingResident = Resident::where('household_id', $household->id)
                    ->where('first_name', $this->first_name)
                    ->where('middle_name', $this->middle_name)
                    ->where('last_name', $this->last_name)
                    ->where('extension_name', $this->extension_name)
                    ->first();

                if ($existingResident) {
                    $validator->errors()->add(
                        'resident', 
                        "A resident with this full name already exists in household #{$household->household_no}."
                    );
                }
            }
        });
    }
}
