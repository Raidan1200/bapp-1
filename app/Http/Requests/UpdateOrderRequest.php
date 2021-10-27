<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('modify orders');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'state' => [
                'required',
                // TODO Define states in some central place
                Rule::in(['fresh', 'deposit_paid', 'interim_paid', 'final_paid', 'cancelled', 'not_paid']),
            ],
            'notes' => [
                'nullable',
            ],
            'bookings.*.data.id' => [
                'nullable',
                'exists:bookings,id',
            ],
            'bookings.*.state' => [
                'nullable',
            ],
            'bookings.*.data.starts_time' => [
                'nullable',
                'date_format:H:i',
            ],
            'bookings.*.data.ends_time' => [
                'nullable',
                'date_format:H:i',
            ],
            'bookings.*.data.package_name' => [
                'required',
                'max:255',
            ],
            'bookings.*.data.is_flat' => [
                'required',
            ],
            'bookings.*.data.quantity' => 'required|integer',
            'bookings.*.data.unit_price' => [
                'required',
                // 'integer,'
            ],
            'bookings.*.data.vat' => [
                'required',
                // 'numeric,'
            ],
            'bookings.*.data.deposit' => [
                'required',
                // 'numeric,'
            ],
        ];
    }
}
