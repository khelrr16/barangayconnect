<?php

namespace App\Http\Requests\Health;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MedicineBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'batch_number' => [
                'required',
                'string',
                'max:255',
                $this->getUniqueRule(),
            ],
            'medicine_id' => 'required|integer',
            'manufacturer' => 'required|string|max:255',
            'received_date' => 'required|date|before_or_equal:today',
            'expiry_date' => 'required|date|after:today',
            'quantity_received' => 'required|integer',
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
        ];

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        // Clean up inputs before validation
        $this->merge([
            'batch_number' => strtoupper(trim($this->batch_number ?? '')),
        ]);
    }

    protected function getUniqueRule()
    {
        $rule = Rule::unique('medicine_batches', 'batch_number');

        // If this is an update, ignore the current record
        if ($this->route('medicine_batch')) {
            $rule->ignore($this->route('medicine_batch')->id);
        }

        // Add soft delete scope if you use soft deletes
        // $rule->whereNull('deleted_at');

        return $rule;
    }

}
