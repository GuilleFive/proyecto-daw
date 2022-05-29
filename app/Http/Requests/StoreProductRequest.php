<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
       return Auth::user()->hasPermissionTo('create_products');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        return [
            'name' => 'required|string',
            'description' => 'required|string|max:140',
            'stock' => 'required|int|min:0|max:255',
            'price' => 'required|numeric|between:3,5000.99',
        ];
    }
}
