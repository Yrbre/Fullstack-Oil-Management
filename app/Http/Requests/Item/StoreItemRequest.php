<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
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
            'item_id'       => 'required|integer|unique:ic_item_mst,item_id',
            'item_no'       => 'required|string|unique:ic_item_mst,item_no',
            'item_desc'     => 'required|string',
            'item_uom'      => 'required|string',
            'item_glclass'  => 'required|string',
            'current_stock' => 'required|string',
        ];
    }
}
