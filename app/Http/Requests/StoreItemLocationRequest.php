<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemLocationRequest extends FormRequest
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
            'item_id'           => 'required|exists:ic_item_mst,id',
            'warehouse_id'      => 'required|exists:warehouses,id',
            'orgn_code'         => 'required|string|max:255',
            'vendor_lot'        => 'required|string|max:255',
            'production_date'   => 'required|date_format:Y-m',
            'package'           => 'required|string|max:255',
            'qty_unit'          => 'required|numeric|min:0',
            'qty_weight'        => 'required|numeric|min:0',
            'received_date'     => 'required|date_format:Y-m-d',
            'notes'             => 'nullable|string|max:255',
        ];
    }
}
