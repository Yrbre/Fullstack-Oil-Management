<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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
            'item_id'       => 'sometimes|integer|unique:ic_item_mst,item_id,' . $this->route('item_master'),
            'item_no'       => 'sometimes|string|unique:ic_item_mst,item_no,' . $this->route('item_master'),
            'item_desc'     => 'sometimes|string',
            'orgn_code'     => 'sometimes|string',
            'item_uom'      => 'sometimes|string',
            'item_glclass'  => 'sometimes|string',
            'current_stock' => 'sometimes|string',
        ];
    }
}
