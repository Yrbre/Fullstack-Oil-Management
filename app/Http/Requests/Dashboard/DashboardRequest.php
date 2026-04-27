<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class DashboardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'month' => 'required|integer|min:1|max:12',
            'year'  => 'required|integer|min:2000|max:' . now()->year,
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'month'     => $this->month ?? now()->month,
            'year'      => $this->year ?? now()->year,
        ]);
    }
}
