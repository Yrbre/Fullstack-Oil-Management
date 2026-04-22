<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransactionRequest extends FormRequest
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
            'item_id'           => 'required|string',
            'orgn_code'         => 'required|string',
            'trans_date'        => 'required|date',
            'doc_type'          => 'required|string',
            'whse_code'         => 'required|string',
            'whse_loc'          => 'required|string',
            'trans_qty'         => 'required|numeric|min:0',
            'catatan'           => 'nullable|string',
            'item_uom'          => 'required|string',
        ];
    }
}
