<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\CheckUserRoleTrait as CheckRole;
use App\Traits\FileAttachmentsTrait as FileUploader;
use Illuminate\Http\Request;
use App\Models\Incident;
use App\Http\Resources\IncidentResource;
use Validator;
use Response;
use DB;

class IncidentsController extends Controller
{
    //Add Incident
    public function addIncident(Request $request)
    {
        try
        {
            Db::beginTransaction();
            
            $checkRole = CheckRole::checkUserAdminRole();
            
            if(!$checkRole)
            {
                $data = [
                    "message" => "You are unauthorised to create Incident",
                    "errors" => '',
                    "error" => true 
                ];
                return response()->json($data,500);
            }

            $validator = Validator::make($request->all(), [
                'checkin_residents' => 'required',
                'incident_datetime' => 'required',
                'incident_severity' => 'required',
                'incident_reason' => 'required',
                'incident_description' => 'required',
                'incident_desc_external_report' => 'required',
                'incident_external_reporting' => 'required',
                'status' => 'required',
                'employee_email' => 'required',
                'incident_closing_date' => 'required',
                'closing_employee_email' => 'required',
            ]);

            if ($validator->fails()) {
                
                $errors = $validator->errors();

                $data = [
                    "message" => "Validation Errors",
                    "errors" => $errors,
                    "error" => true 
                ];

                return response()->json($data,400); // validation errror
            }

            $incident = new Incident();
            $incident->checkin_residents = implode(', ', $request->checkin_residents);
            $incident->incident_datetime = $request->incident_datetime;
            $incident->incident_severity = $request->incident_severity;
            $incident->incident_reason = implode(', ', $request->incident_reason);
            $incident->incident_description = $request->incident_description;
            $incident->incident_desc_external_report = $request->incident_desc_external_report;
            $incident->incident_external_reporting = $request->incident_external_reporting;
            $incident->status = $request->status;
            $incident->employee_email = $request->employee_email;
            $incident->incident_closing_date = $request->incident_closing_date;
            $incident->closing_employee_email = $request->closing_employee_email;

            if($request->hasfile('videos_attachment'))
            {
                $videos = [];
                $videos_attachment = $request->videos_attachment;

                foreach($videos_attachment as $id => $video_ath)
                {
                    $filename = FileUploader::uploadFile($video_ath,'incident/video_attachment',$id.'_video_attachment');
                    $path = url('/').'/storage/incident/video_attachment/'.$filename;
                    $path_data = [
                        'id' => $id,
                        'path' => $path
                    ];
                    array_push($videos,$path_data);
                    $path_data = '';
                }
                $incident->videos_attachment = json_encode($videos);

            }

            if($incident->save())
            {
                DB::commit();

                $data = [
                    "message" => "Incident Added Successfully!",
                    "property" => new IncidentResource($incident),
                    "error"=>false,  
                ];

                return response()->json($data,200);
            }
            else{


                $data = [
                    "message" => "Error Occured while saving data",
                    "errors" => '',
                    "error" => true 
                ];
                
                return response()->json($data,500);
            
            }

        }
        catch (\Exception $e){
            DB::rollback();
            
            $data = [
                "message" => "Error Occured",
                "errors" => $e->getMessage(),
                "error" => true 
            ];
            return response()->json( $data,500); // server error
        }
    }
}
