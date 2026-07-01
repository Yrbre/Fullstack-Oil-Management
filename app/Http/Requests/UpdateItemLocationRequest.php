<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemLocationRequest extends FormRequest
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
            'item_id'           => 'sometimes|exists:ic_item_mst,id',
            'warehouse_id'      => 'sometimes|exists:warehouses,id',
            'orgn_code'         => 'sometimes|string|max:255',
            'vendor_lot'        => 'sometimes|string|max:255',
            'production_date'   => 'nullable|date_format:Y-m',
            'package'           => 'sometimes|string|max:255',
            'qty_unit'          => 'sometimes|numeric|min:0',
            'qty_weight'        => 'sometimes|numeric|min:0',
            'received_date'     => 'sometimes|date_format:Y-m-d',
            'notes'             => 'nullable|string|max:255',
        ];
    }
}
