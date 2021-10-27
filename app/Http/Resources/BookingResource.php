<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'config' => $this->config,

            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'interval' => null,

            'package_name' => $this->package_name,
            'unit_price' => $this->unit_price,
            'quantity' => $this->quantity,
            'vat' => $this->vat,
            'deposit'=> $this->deposit,
            'is_flat'=> $this->is_flat,

            'room_id' => $this->room_id,
            'package_id' => $this->package_id,
            'created_at' => $this->created_at,
        ];
    }
}
