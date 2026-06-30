<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseRequest extends FormRequest
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
            'tag' => strtoupper(trim($this->tag)),
        ]);
    }
    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'tag'           => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ];
    }
}
