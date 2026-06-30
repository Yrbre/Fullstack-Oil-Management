<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => strtoupper(trim($this->name)),
            'code' => strtoupper(trim($this->code)),
        ]);
    }

    public function rules(): array
    {
        return [
            'name'          => 'sometimes|string|max:255',
            'code'          => 'sometimes|string|max:100',
        ];
    }
}
