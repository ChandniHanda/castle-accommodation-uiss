<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\FileAttachmentsTrait as FileUploader;
use App\Traits\CheckUserRoleTrait as CheckRole;
use App\Models\CheckIn;
use App\Models\check_out;

use App\Http\Resources\CheckInResource;
use Auth;
use Response;
use Validator;
use DB;


class OccupancyController extends Controller
{
    //

    ###CheckIn functions

    public function createCheckIn(Request $r){
        try{
            
            Db::beginTransaction();

            $checkRole = CheckRole::checkUserAdminRole();

            if(!$checkRole)
            {
                $data = [
                    "message" => "You are unauthorised to create CheckIn",
                    "errors" => '',
                    "error" => true 
                ];
                return response()->json($data,500);
            }

            $validator = Validator::make($r->all(), [
                'resident_fullname' => 'required',
                'date_of_birth' => 'required',
                'mobile_number' => 'required',
                'customer_id' => 'required',
                'terms_conditions'=> 'required',
                'evicted_previous_accomodation' => 'required',
                'resident_signature'=> 'required',
                'comment'=> 'required'
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

            $checkIn = new CheckIn();
            $checkIn->resident_fullname = $r->resident_fullname;
            $checkIn->resident_nickname = $r->resident_nickname;
            $checkIn->date_of_birth = $r->date_of_birth;
            $checkIn->mobile_number = $r->mobile_number;
            $checkIn->customer_id = $r->customer_id;
            $checkIn->command = $r->command;
            $checkIn->room_no = $r->room_no;
            $checkIn->date = $r->date;
            $checkIn->time_checkin = $r->time_checkin;
            $checkIn->door_code = $r->door_code;
            $checkIn->access_fob = $r->access_fob;
            $checkIn->comment = $r->comment;
            $checkIn->evicted_previous_accomodation = $r->evicted_previous_accomodation;
            $checkIn->terms_conditions = $r->terms_conditions;


            if($r->hasfile('resident_signature'))
            {
                $resident_signature = $r->resident_signature;
                $filename = FileUploader::uploadFile($resident_signature,'checkin_signature','resident_signature');
                $path = url('/').'/storage/checkin_signature/'.$filename;
                $checkIn->resident_signature = json_encode($path);
            }

            if($r->hasfile('videos_attachment'))
            {
                $videos = [];
                $videos_attachment = $r->videos_attachment;
                foreach($videos_attachment as $id => $video_ath)
                {
                    $filename = FileUploader::uploadFile($video_ath,'checkIn/video_attachment',$id.'_video_attachment');
                    $path = url('/').'/storage/checkIn/video_attachment/'.$filename;
                    $path_data = [
                        'id' => $id,
                        'path' => $path
                    ];
                    array_push($videos,$path_data);
                    $path_data = '';
                }
                $checkIn->videos_attachment = json_encode($videos);

            }
            

            if($checkIn->save())
            {
                DB::commit();

                $data = [
                    "message" => "Checked In Successfully!",
                    "property" => new CheckInResource($checkIn),
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

    

    // create checkout functions

    public function createCheckOut(Request $r)
    {
        try{
            
            Db::beginTransaction();

            $checkRole = CheckRole::checkUserAdminRole();

            if(!$checkRole)
            {
                $data = [
                    "message" => "You are unauthorised to create CheckOut",
                    "errors" => '',
                    "error" => true 
                ];
                return response()->json($data,500);
            }


            $validator = Validator::make($r->all(), [
                'checkout_date'=>'required',
                'checkout_time'=>'required',
                'employee_id'=>'required',
                'reason_of_leaving'=>'required'
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


            $checkOut = new check_out();

            $checkOut->checkout_date = $r->checkout_date;
            $checkOut->checkout_time = $r->checkout_time;
            $checkOut->employee_id = $r->employee_id;
            $checkOut->link_checkin_video = $r->link_checkin_video;
            $checkOut->reason_of_leaving = $r->reason_of_leaving;
            $checkOut->repair_maintenance_needed = $r->repair_maintenance_needed;
            $checkOut->procurement_needed = $r->procurement_needed;
            $checkOut->comments = $r->comments;

            if($r->hasfile('videos_attachment'))
            {
                $videos = [];
                $videos_attachment = $r->videos_attachment;
                foreach($videos_attachment as $id => $video_ath)
                {
                    $filename = FileUploader::uploadFile($video_ath,'checkIn/video_attachment',$id.'_video_attachment');
                    $path = url('/').'/storage/checkIn/video_attachment/'.$filename;
                    $path_data = [
                        'id' => $id,
                        'path' => $path
                    ];
                    array_push($videos,$path_data);
                    $path_data = '';
                }
                $checkOut->videos_attachment = json_encode($videos);
            }

            if($checkOut->save())
            {
                DB::commit();

                $data = [
                    "message" => "Checked Out Successfully!",
                   // "property" => new CheckInResource($checkIn),
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
