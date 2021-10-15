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
        return true;
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
            'slug' => 'required|max:255',
            'slogan' => 'nullable|string',
            'description' => 'nullable|string',
            // 'image' => 'nullable|mimes:jpg,jpeg,png,webp',
            'image' => 'nullable|url',
            'capacity' => 'required|integer',
            'venue_id' => 'required|exists:venues,id',
        ];
    }
}
