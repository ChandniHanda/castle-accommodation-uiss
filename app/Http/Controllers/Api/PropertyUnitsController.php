<?php

namespace App\Http\Controllers\Api;
use App\Models\PropertyUnits;
use App\Traits\FileAttachmentsTrait as FileUploader;
use App\Traits\CheckUserRoleTrait as CheckRole;
use App\Http\Resources\PropertyUnitCollection;
use App\Http\Resources\PropertyUnitResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use DB;
use Validator;
use Auth;

class PropertyUnitsController extends Controller
{
    //
    public function addPropertyUnit(Request $r){
       
        Db::beginTransaction();
        try{
            if(Auth::user() && (Auth::user()->role->id == 1 || Auth::user()->role->id == 2))
            { 

                $validator = Validator::make($r->all(), [
                    'property_id' => 'required',
                    'property_unit_number' => 'required',
                    'property_unit_floor' => 'required',
                    'property_unit_type' => 'required',
                    'description'=>'required',
                    'address1'=>'required',
                    'town'=> 'required',
                    'contact_number'=> 'required',
                    'postcode'=> 'required',
                  
                    // 'electric_meter_id'=> 'required',
                    // 'gas_meter_id'=> 'required',
                    // 'water_meter_id'=> 'required',
                    // 'primary_photo'=> 'required',
                    // 'property_video_internal'=> 'required',
                    // 'property_video_external'=> 'required',
                    // 'house_rules'=> 'required',
                    'primary_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'images_attachment.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                    'property_video_internal' => 'mimes:mp4,webm',
                    'videos_attachment.*' => 'mimes:mp4,webm',
                    //'video_upload' => 'mimetypes:video/avi,video/mpeg,video/quicktime|max:102400'
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



                $property_units = new PropertyUnits();
                $property_units->property_id = $r->property_id;
                $property_units->description = $r->description;
                $property_units->room_number = $r->room_number;
                $property_units->property_unit_number = $r->property_unit_number;
                $property_units->property_unit_floor = $r->property_unit_floor;
                $property_units->property_unit_type = $r->property_unit_type;
                $property_units->property_unit_gps = $r->property_unit_gps;

                $property_units->address1 = $r->address1;
                $property_units->address2 = $r->address2;

                $property_units->town = $r->town;
                $property_units->postcode = $r->postcode;
                $property_units->contact_number = $r->contact_number;
              
                $property_units->electric_meter_id = $r->electric_meter_id; 
                $property_units->gas_meter_id = $r->gas_meter_id; 
                $property_units->water_meter_id = $r->water_meter_id; 
                $property_units->solar_panels = $r->solar_panels;

                $property_units->hmo_license = $r->hmo_license; 
                $property_units->hmo_license_expiry_date = $r->hmo_license_expiry_date;

                $property_units->insurance_policy = $r->insurance_policy; 
                $property_units->insurance_policy_exiry_date = $r->insurance_policy_exiry_date;

                $property_units->eicr = $r->eicr; 
                $property_units->eicr_exiry_date = $r->eicr_exiry_date;

                $property_units->gas_certificate = $r->gas_certificate; 
                $property_units->gas_certificate_exiry_date = $r->gas_certificate_exiry_date;

                $property_units->pat_test = $r->pat_test; 
                $property_units->pat_test_exiry_date = $r->pat_test_exiry_date;

                $property_units->epc_certificate = $r->epc_certificate; 
                $property_units->epc_certificate_exiry_date = $r->epc_certificate_exiry_date;

                $property_units->house_rules = $r->house_rules;

                $property_units->property_video_internal_link = $r->property_video_internal_link;
                $property_units->property_video_external_link = $r->property_video_external_link;
                // uploading all images & videos
                if($r->hasfile('primary_photo'))
                {
                    $primary_image = $r->primary_photo;
                    $filename = FileUploader::uploadFile($primary_image,'property_units','primary_photo');
                    $path = url('/').'/storage/property_units/'.$filename;
                    $property_units->primary_photo = json_encode($path);
                }

                if($r->hasfile('images_attachment'))
                {
                    $images_attachment = $r->images_attachment;
                    $images = [];

                    foreach($images_attachment as $id => $img_ath)
                    {
                        $path = '';  $path_data = [];

                        $filename = FileUploader::uploadFile($img_ath,'property_units/image_attachment',$id.'_image_attached');
                        
                        $path = url('/').'/storage/property_units/image_attachment/'.$filename;
                        
                        $path_data = [
                            'id' => $id,
                            'path'=> $path
                        ];
                        array_push($images,$path_data);

                        
                    }
                    $property_units->images_attachment = json_encode($images);

                }
   
                if($r->hasfile('property_video_internal'))
                {
                    $property_video_internal = $r->property_video_internal;
                    $filename = FileUploader::uploadFile($property_video_internal,'property_units/internal_video','internal_video');
                    $path = url('/').'/storage/property_units/internal_video/'.$filename;
                    $property_units->property_video_internal = json_encode($path);

                }

   
                if($r->hasfile('property_video_external'))
                {
                    $property_video_external = $r->property_video_external;
                    $filename = FileUploader::uploadFile($property_video_external,'property_units/external_video','external_video');
                    $path = url('/').'/storage/property_units/external_video/'.$filename;
                    $property_units->property_video_external = json_encode($path);

                }
   
   
                if($r->hasfile('videos_attachment'))
                {
                    $videos = [];
                    $videos_attachment = $r->videos_attachment;
                    foreach($videos_attachment as $id => $video_ath)
                    {
                        $filename = FileUploader::uploadFile($video_ath,'property_units/video_attachment',$id.'_video_attachment');
                        $path = url('/').'/storage/property_units/video_attachment/'.$filename;
                        $path_data = [
                            'id' => $id,
                            'path' => $path
                        ];
                        array_push($videos,$path_data);
                        $path_data = '';
                    }
                    $property_units->videos_attachment = json_encode($videos);

                }

                if($property_units->save())
                {
                    DB::commit();

                    $data = [
                        "message" => "Property Unit added Successfully!",
                        "property" => new PropertyUnitResource($property_units),
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
            else{
                $data = [
                    "message" => "You are unauthorised to create a property_units unit!",
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
            return response()->json($e->getMessage(),500); // server error
        }
    }

    public function updatePropertyUnit(Request $r,$id)
    {   

        Db::beginTransaction();

         
        try{

            if(Auth::user() && (Auth::user()->role->id == 1 || Auth::user()->role->id == 2))
            {
                $validator = Validator::make($r->all(), [
                    'property_id' => 'required',
                    'property_unit_number' => 'required',
                    'property_unit_floor' => 'required',
                    'property_unit_type' => 'required',
                    'description'=>'required',
                    'address1'=>'required',
                    'town'=> 'required',
                    'contact_number'=> 'required',
                    'postcode'=> 'required',
                  
                    // 'electric_meter_id'=> 'required',
                    // 'gas_meter_id'=> 'required',
                    // 'water_meter_id'=> 'required',
                    // 'primary_photo'=> 'required',
                    // 'property_video_internal'=> 'required',
                    // 'property_video_external'=> 'required',
                    // 'house_rules'=> 'required',
                    'primary_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'images_attachment.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                    'property_video_internal' => 'mimes:mp4,webm',
                    'videos_attachment.*' => 'mimes:mp4,webm',
                    //'video_upload' => 'mimetypes:video/avi,video/mpeg,video/quicktime|max:102400'
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


                $property_units = PropertyUnits::find($id);

                if($property_units)
                {
                    $property_units->property_id = $r->property_id;
                    $property_units->description = $r->description;
                    $property_units->room_number = $r->room_number;
                    $property_units->property_unit_number = $r->property_unit_number;
                    $property_units->property_unit_floor = $r->property_unit_floor;
                    $property_units->property_unit_type = $r->property_unit_type;
                    $property_units->property_unit_gps = $r->property_unit_gps;
    
                    $property_units->address1 = $r->address1;
                    $property_units->address2 = $r->address2;
    
                    $property_units->town = $r->town;
                    $property_units->postcode = $r->postcode;
                    $property_units->contact_number = $r->contact_number;
                  
                    $property_units->electric_meter_id = $r->electric_meter_id; 
                    $property_units->gas_meter_id = $r->gas_meter_id; 
                    $property_units->water_meter_id = $r->water_meter_id; 
                    $property_units->solar_panels = $r->solar_panels;
    
                    $property_units->hmo_license = $r->hmo_license; 
                    $property_units->hmo_license_expiry_date = $r->hmo_license_expiry_date;
    
                    $property_units->insurance_policy = $r->insurance_policy; 
                    $property_units->insurance_policy_exiry_date = $r->insurance_policy_exiry_date;
    
                    $property_units->eicr = $r->eicr; 
                    $property_units->eicr_exiry_date = $r->eicr_exiry_date;
    
                    $property_units->gas_certificate = $r->gas_certificate; 
                    $property_units->gas_certificate_exiry_date = $r->gas_certificate_exiry_date;
    
                    $property_units->pat_test = $r->pat_test; 
                    $property_units->pat_test_exiry_date = $r->pat_test_exiry_date;
    
                    $property_units->epc_certificate = $r->epc_certificate; 
                    $property_units->epc_certificate_exiry_date = $r->epc_certificate_exiry_date;
    
                    $property_units->house_rules = $r->house_rules;
    
                    $property_units->property_video_internal_link = $r->property_video_internal_link;
                    $property_units->property_video_external_link = $r->property_video_external_link;
                    // uploading all images & videos
                    if($r->hasfile('primary_photo'))
                    {
                        $primary_image = $r->primary_photo;
                        $filename = FileUploader::uploadFile($primary_image,'property_units','primary_photo');
                        $path = url('/').'/storage/property_units/'.$filename;
                        $property_units->primary_photo = json_encode($path);
                    }
    
                    if($r->hasfile('images_attachment'))
                    {
                        $images_attachment = $r->images_attachment;
                        $images = [];
    
                        foreach($images_attachment as $id => $img_ath)
                        {
                            $path = '';  $path_data = [];
    
                            $filename = FileUploader::uploadFile($img_ath,'property_units/image_attachment',$id.'_image_attached');
                            
                            $path = url('/').'/storage/property_units/image_attachment/'.$filename;
                            
                            $path_data = [
                                'id' => $id,
                                'path'=> $path
                            ];
                            array_push($images,$path_data);
    
                            
                        }
                        $property_units->images_attachment = json_encode($images);
    
                    }
       
                    if($r->hasfile('property_video_internal'))
                    {
                        $property_video_internal = $r->property_video_internal;
                        $filename = FileUploader::uploadFile($property_video_internal,'property_units/internal_video','internal_video');
                        $path = url('/').'/storage/property_units/internal_video/'.$filename;
                        $property_units->property_video_internal = json_encode($path);
    
                    }
    
       
                    if($r->hasfile('property_video_external'))
                    {
                        $property_video_external = $r->property_video_external;
                        $filename = FileUploader::uploadFile($property_video_external,'property_units/external_video','external_video');
                        $path = url('/').'/storage/property_units/external_video/'.$filename;
                        $property_units->property_video_external = json_encode($path);
    
                    }
       
       
                    if($r->hasfile('videos_attachment'))
                    {
                        $videos = [];
                        $videos_attachment = $r->videos_attachment;
                        foreach($videos_attachment as $id => $video_ath)
                        {
                            $filename = FileUploader::uploadFile($video_ath,'property_units/video_attachment',$id.'_video_attachment');
                            $path = url('/').'/storage/property_units/video_attachment/'.$filename;
                            $path_data = [
                                'id' => $id,
                                'path' => $path
                            ];
                            array_push($videos,$path_data);
                            $path_data = '';
                        }
                        $property_units->videos_attachment = json_encode($videos);


                        if($property_units->save())
                        {
                            DB::commit();
    
                            $data = [
                                "message" => "Property Unit Updated Successfully!",
                                "property" => new PropertyUnitResource($property),
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


                    if($property_units->save())
                    {
                        DB::commit();
    
                        $data = [
                            "message" => "Property Unit updated Successfully!",
                            "property" => new PropertyUnitResource($property_units),
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
                else{
                    
                    $data = [
                        "message" => "Invalid property ID, Property not found!",
                        "errors" => '',
                        "error" => true 
                    ];
                    return response()->json($data,500); // server error
                
                }

            }
            else{
                $data = [
                    "message" => "You are unauthorised",
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


    public function propertyListing(){

        try{

            $checkRole = CheckRole::checkUserRole();

            if(!$checkRole)
            {
                $data = [
                    "message" => "You are unauthorised to get property_units Listing!",
                    "errors" => '',
                    "error" => true 
                ];
                return response()->json($data,500);
            }

            $property_units = PropertyUnits::paginate(5);

            $data = [
                "message" => "Property Units Listing",
                "property units" => new PropertyUnitCollection($property_units),
                "error"=>false,  
            ];

            return response()->json($data,200);


        }
        catch (\Exception $e){
            DB::rollback();
            
            $data = [
                "message" => "Error Occured",
                "errors" => $e->getMessage(),
                "error" => true 
            ];
            return response()->json($e->getMessage(),500); // server error
        }
    }

    public function getProperyUnitByPID($id){

        try{

            
            $checkRole = CheckRole::checkUserRole();

            if(!$checkRole)
            {
                $data = [
                    "message" => "You are unauthorised to get property_units Listing!",
                    "errors" => '',
                    "error" => true 
                ];
                return response()->json($data,500);
            }

            $property_units = PropertyUnits::where('property_id',$id)->paginate(5);

          //  dd($property_units);

            $data = [
                "message" => "Property Units",
                "property units" => new PropertyUnitCollection($property_units),
                "error"=>false,  
            ];

            return response()->json($data,200);


        }
        catch (\Exception $e){
            DB::rollback();
            
            $data = [
                "message" => "Error Occured",
                "errors" => $e->getMessage(),
                "error" => true 
            ];
            return response()->json($e->getMessage(),500); // server error
        }

    }

    public function getpropertyUnitbyID($id)
    {
        try{
                $checkRole = CheckRole::checkUserRole();

                if(!$checkRole)
                {
                    $data = [
                        "message" => "You are unauthorised to get property_units Listing!",
                        "errors" => '',
                        "error" => true 
                    ];
                    return response()->json($data,500);
                }
            
               $property_units = PropertyUnits::find($id);

               if($property_units)
               {
                    $data = [
                        "message" => "Property Unit Found!",
                        "property" => new PropertyUnitResource($property_units),
                        "error"=>false,  
                    ];

                    return response()->json($data,200);
               }
               else{

                $data = [
                    "message" => "Invalid property unit ID, Property unit not found!",
                    "errors" => '',
                    "error" => true 
                ];
                return response()->json($data,500); // server error


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
