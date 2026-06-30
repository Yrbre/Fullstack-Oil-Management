<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
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
            'name'          => 'required|string|max:255',
            'code'          => 'required|string|max:100',
        ];
    }
}
