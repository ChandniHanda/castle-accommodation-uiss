<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckInResource extends JsonResource
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
            'resident_fullname' => $this->resident_fullname,
            'resident_nickname' => $this->resident_nickname,
            'date_of_birth' => $this->date_of_birth,
            'mobile_number' => $this->mobile_number,
            'customer_id' => $this->customer_id,
            'command'=> $this->command,
            'room_no'=> $this->room_no,
            'date' => $this->date,
            'time_checkin' => $this->time_checkin,
            'door_code' => $this->door_code,
            'access_fob' => $this->access_fob,
            'comment' => $this->comment,
            'evicted_previous_accomodation' => $this->evicted_previous_accomodation,
            'terms_conditions'=> $this->terms_conditions,
            'resident_signature'=>json_decode($this->resident_signature),
            'videos_attachment' => json_decode($this->videos_attachment)

        ];

    }
}
