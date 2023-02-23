<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $property = [
            'id' => $this->id,
            'property_name' => $this->property_name,
            'number_of_units' => $this->number_of_units,
            'description'=> $this->description,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'town' => $this->town,
            'contact_number' => $this->contact_number,
            'postcode' => $this->postcode,
            'gps_coordinates' => $this->gps_coordinates,
            'primary_manager_id' => $this->primary_manager_id,
            'regional_manager_id' => $this->regional_manager_id,
            'electric_meter_id' => $this->electric_meter_id,
            'gas_meter_id'=> $this->gas_meter_id,
            'water_meter_id' => $this->water_meter_id,
            'solar_panels'=> $this->solar_panels,
            'hmo_license' => $this->hmo_license,
            'hmo_license_expiry_date'=> $this->hmo_license_expiry_date,
            'insurance_policy' => $this->insurance_policy,
            'insurance_policy_exiry_date' => $this->insurance_policy_exiry_date,
            'eicr' => $this->eicr,
            'eicr_exiry_date' => $this->eicr_exiry_date,
            'gas_certificate'=> $this->gas_certificate,
            'gas_certificate_exiry_date'=> $this->gas_certificate_exiry_date,
            'pat_test' => $this->pat_test,
            'pat_test_exiry_date' => $this->pat_test_exiry_date,
            'epc_certificate' => $this->epc_certificate,
            'epc_certificate_exiry_date'=> $this->epc_certificate_exiry_date,
            'house_rules' => $this->house_rules,
            'primary_photo' => json_decode($this->primary_photo),
            'images_attachment' => json_decode($this->images_attachment),
            'property_video_internal'=> json_decode($this->property_video_internal),
            'property_video_external'=> json_decode($this->property_video_external),
            'videos_attachment'=>json_decode($this->videos_attachment),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
       
        return $property;
    }
}
