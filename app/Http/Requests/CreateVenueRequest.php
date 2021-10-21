<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVenueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create venues') || $this->user()->can('modify venues');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'slug' => 'required|max:255',
            'email' => 'nullable|email',
            'invoice_blocks' => 'nullable|json',
            'reminder_delay' => 'required|integer|min:0',
            'check_delay' => 'required|integer|min:0|gte:reminder_delay',
            'cancel_delay' => 'required|integer|min:0|gte:check_delay',
            'payment_delay' => 'required|integer|min:0|gte:check_delay',
            'invoice_id_format' => 'required'
        ];
    }
}
