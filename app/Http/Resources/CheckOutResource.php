<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckOutResource extends JsonResource
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
            'checkout_date' => $this->checkout_date,
            'checkout_time' => $this->checkout_time,
            'employee_id' => $this->employee_id,
            'link_checkin_video'=> $this->link_checkin_video,
            'reason_of_leaving'=> $this->reason_of_leaving
        ];
    }
}
