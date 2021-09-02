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
            'name' => 'required',
            'slug' => 'required',
            'email' => 'nullable|email',
            'invoice_blocks' => 'nullable|json',
            'reminder_delay' => 'nullable|integer',
            'check_delay' => 'nullable|integer|gte:reminder_delay',
            'delete_delay' => 'nullable|integer|gte:check_delay',
        ];
    }
}
