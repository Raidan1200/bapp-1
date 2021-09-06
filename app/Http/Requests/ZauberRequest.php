<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ZauberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->id === $this->venue->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer.first_name' => 'required',
            'customer.last_name' => 'required',
            'customer.email' => 'required',
            'customer.company' => 'sometimes',
            'customer.street' => 'required',
            'customer.street_no' => 'required',
            'customer.zip' => 'required',
            'customer.city' => 'required',
            'customer.phone' => 'required',

            'bookings.*.starts_at' => 'required|date',
            'bookings.*.ends_at' => 'required|date',
            'bookings.*.package_id' => 'required|exists:packages,id',
            'bookings.*.room_id' => 'required|exists:rooms,id',
            'bookings.*.quantity' => 'required|integer',
        ];
    }
}
