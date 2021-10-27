<?php

namespace App\Http\Resources;

use App\Http\Resources\BookingResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'state' => $this->state,
            'starts_at' => $this->starts_at,
            'notes' => $this->notes,
            'cash_payment' => $this->cash_payment,

            'deposit_invoice_id' => $this->deposit_invoice_id,
            'deposit_invoice_at' => $this->deposit_invoice_at,
            'deposit_amount' => $this->deposit_amount,
            'deposit_email_at' => $this->deposit_email_at,
            'deposit_paid_at' => $this->deposit_paid_at,
            'deposit_reminder_at' => $this->deposit_reminder_at,

            'interim_invoice_id' => $this->interim_invoice_id,
            'interim_invoice_at' => $this->interim_invoice_at,
            'interim_amount' => $this->interim_amount,
            'interim_email_at' => $this->interim_email_at,
            'interim_paid_at' => $this->interim_paid_at,
            'interim_is_final' => $this->interim_is_final,

            'final_invoice_id' => $this->final_invoice_id,
            'final_invoice_at' => $this->final_invoice_at,
            'final_email_at' => $this->final_email_at,
            'final_paid_at' => $this->final_paid_at,

            'cancelled_at' => $this->cancelled_at,

            'config' => $this->config,

            'bookings' => BookingResource::collection($this->bookings),
            'items' => [],
            'customer' => CustomerResource::make($this->customer),

            'created_at' => $this->created_at,
        ];
    }
}
