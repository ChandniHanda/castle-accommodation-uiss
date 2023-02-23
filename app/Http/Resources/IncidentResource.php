<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IncidentResource extends JsonResource
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
            'checkin_residents' => $this->checkin_residents,
            'incident_datetime' => $this->incident_datetime,
            'incident_severity' => $this->incident_severity,
            'videos_attachment' => json_decode($this->videos_attachment),
            'incident_reason' => $this->incident_reason,
            'incident_description' => $this->incident_description,
            'incident_desc_external_report' => $this->incident_desc_external_report,
            'incident_external_reporting' => $this->incident_external_reporting,
            'status' => $this->status,
            'employee_email' => $this->employee_email,
            'incident_closing_date' => $this->incident_closing_date,
            'closing_employee_email' => $this->closing_employee_email

        ];
    }
}
