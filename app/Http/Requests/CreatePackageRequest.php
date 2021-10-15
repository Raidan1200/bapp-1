<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class CreatePackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create packages') || $this->user()->can('modify packages');
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
            'slogan'          => 'required|max:255',
            'description'     => 'nullable|string',
            // 'image' => 'sometimes|mimes:jpg,jpeg,png,webp',
            'image' => 'nullable|url',
            'starts_at'       => 'required|date',
            'ends_at'         => 'required|date',
            'opens_at'        => 'required|string', // LATER: Needs better validation
            'closes_at'       => 'required|string', // LATER: Needs better validation
            'min_occupancy'   => 'sometimes|integer|min:0',
            'unit_price'      => 'required|numeric|min:0',
            'vat'             => 'required|numeric|min:0',
            'is_flat'         => 'sometimes|string',
            'deposit'         => 'required|numeric|min:0',
            'venue_id'        => 'required|exists:venues,id'
        ];
    }
}
