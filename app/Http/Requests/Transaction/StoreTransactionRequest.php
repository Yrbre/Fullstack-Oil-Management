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
            'trans_id'          => 'required|integer|unique:ic_trans_inv',
            'item_id'           => 'required|integer|exists:ic_item_mst,item_id',
            'status'            => 'required|string',
            'item_no'           => 'required|string',
            'item_desc'         => 'required|string',
            'item_uom'          => 'required|string',
            'orgn_code'         => 'required|string',
            'whse_code'         => 'required|string',
            'whse_loc'          => 'required|string',
            'doc_type'          => 'required|string',
            'adj_type'          => 'required|string',
            'reason_code'       => 'required|string',
            'creation_date'     => 'required|datetime',
            'trans_date'        => 'required|date',
            'tgl'               => 'required|string',
            'bln'               => 'required|string',
            'thn'               => 'required|string',
            'periode'           => 'required|string',
            'trans_qty'         => 'required|numeric',
            'catatan'           => 'nullable|string',
            'bb_qty'            => 'required|numeric',
            'in_qty' => [
                Rule::when(
                    fn($input) => $input->doc_type === 'PORC' ||
                        ($input->doc_type === 'ADJI' && $input->adj_type === 'IN'),
                    ['required', 'numeric', 'min:0'],
                    ['nullable', 'numeric'] // jika kondisi tidak terpenuhi
                ),
            ],
            'out_qty' => [
                Rule::when(
                    fn($input) => $input->doc_type === 'PORC' ||
                        ($input->doc_type === 'ADJI' && $input->adj_type === 'OUT'),
                    ['required', 'numeric', 'min:0'],
                    ['nullable', 'numeric'] // jika kondisi tidak terpenuhi
                ),

            ],
            'eb_qty'           => 'required|numeric',
            'created_by'       => 'required|string',
        ];
    }
}
