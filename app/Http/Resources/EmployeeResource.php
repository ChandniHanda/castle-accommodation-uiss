<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'user_id' => $this->user_id,
            'name' => $this->name,
            'email' => $this->email,
            'photo' => json_decode($this->photo),
            'card_id' => $this->card_id,
            'job_title' => $this->job_title,
            'mobile_number_work' => $this->mobile_number_work,
            'mobile_number_private' => $this->mobile_number_private,
            'employee_group_id' => $this->employee_group_id,
            'manager_id' => $this->manager_id,
            'about_me' => $this->about_me,
            'property_id' => $this->property_id

        ];
    }
}
