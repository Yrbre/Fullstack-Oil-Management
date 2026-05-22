<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'item_id'       => 'required|string',
            'orgn_code'     => 'required|string',
            'trans_date'    => 'required|date',
            'doc_type'      => 'required|string',
            'adj_type'    => [
                'required',
                'string',
                Rule::in(['PORC', 'CONS']),
            ],
            'whse_code'     => 'required|string',
            'whse_loc'      => 'required|string',
            'current_stock' => 'required|numeric',
            'trans_qty'     => [
                'required',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d{1})?$/',
            ],
            'catatan'  => 'required|string',
            'item_uom' => 'required|string',
        ];
    }

    // ✅ Tambahkan ini
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $docType      = $this->doc_type;
            $transQty     = (float) $this->trans_qty;
            $currentStock = (float) $this->current_stock;

            if ($docType === 'CONS' && $transQty > $currentStock) {
                $validator->errors()->add(
                    'trans_qty',
                    'Quantity tidak boleh melebihi current stock (' . number_format($currentStock, 0, ',', '.') . ').'
                );
            }

            // Jika ADJI PORC juga tidak boleh minus
            if ($docType === 'ADJI' && $this->adj_type === 'PORC' && $transQty > $currentStock) {
                $validator->errors()->add(
                    'trans_qty',
                    'Quantity tidak boleh melebihi current stock (' . number_format($currentStock, 0, ',', '.') . ').'
                );
            }
        });
    }
}
