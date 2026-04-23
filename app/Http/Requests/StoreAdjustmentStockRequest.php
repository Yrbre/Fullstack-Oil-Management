<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdjustmentStockRequest extends FormRequest
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
            'orgn_code'         => 'required|string',
            'status'            => 'required|string',
            'trans_date'        => 'required|date',
            'adj_type'          => 'required|string',
            'item_id'           => 'required|string',
            'whse_code'         => 'required|string',
            'whse_loc'          => 'required|string',
            'trans_qty'         => 'required|numeric',
            'item_uom'          => 'required|string',
            'catatan'           => 'required|string',
            'doc_type'          => 'required|string|in:ADJI',

        ];
    }
}
