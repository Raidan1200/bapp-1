<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create rooms') || $this->user()->can('modify rooms');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'slogan' => 'sometimes',
            'description' => 'sometimes',
            'image' => 'sometimes|mimes:jpg,jpeg,png,webp',
            'capacity' => 'required|integer',
            'venue_id' => 'required|exists:venues,id',
        ];
    }
}
