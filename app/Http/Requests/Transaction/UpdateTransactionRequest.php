<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
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
            'status'                    => 'required|string',
            'whse_code'                 => 'required|string',
            'whse_loc'                  => 'required|string',
            'trans_date'                => 'required|date',
            'tgl'                       => 'required|string',
            'bln'                       => 'required|string',
            'thn'                       => 'required|string',
            'periode'                   => 'required|string',
            'catatan'                   => 'nullable|string',
            'update_date'               => 'required|datetime',
            'updated_by'                => 'required|exists:users,id',
        ];
    }
}
