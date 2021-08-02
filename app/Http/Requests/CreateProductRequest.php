<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create products') || $this->user()->can('modify products');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $request['is_flat'] = $request->boolean('is_flat');

        return [
            'name'            => 'required|max:255',
            'slogan'          => 'sometimes',
            'description'     => 'sometimes',
            'image'           => 'sometimes|mimes:jpg,jpeg,png,webp',
            'starts_at'       => 'required|date',
            'ends_at'         => 'required|date',
            // TODO IMPORTANT: This is actually wrong
            'opens_at'        => ['required', 'min:0', 'max:24', fn($_, $value, $fail) => $value >= $request->closes_at ? $fail('Opening time cannot be equal to or after closing time.') : null],
            'closes_at'       => 'required|min:0|max:24',
            'min_occupancy'   => 'sometimes|integer',

            'price'           => 'required|integer',
            'vat'             => 'required|numeric',
            'is_flat'         => 'sometimes|boolean',
            'deposit'         => 'required|numeric',

            'venue_id'        => 'exists:venues,id'
        ];
    }
}
