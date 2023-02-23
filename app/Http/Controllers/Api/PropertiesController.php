<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Properties;
use App\Traits\FileAttachmentsTrait as FileUploader;
use App\Http\Resources\PropertyResource;
use App\Http\Resources\PropertyCollection;
use Response;
use DB;
use Validator;
use Auth;

class PropertiesController extends Controller
{
    
    public function addProperty(Request $r){
        Db::beginTransaction();
       
        try{
            if(Auth::user() && (Auth::user()->role->id == 1 || Auth::user()->role->id == 2))
            {    
                $validator = Validator::make($r->all(), [
                    'property_name' => 'required',
                    'number_of_units' => 'required',
                    'description'=>'required',
                    'address1'=>'required',
                    'town'=> 'required',
                    'contact_number'=> 'required',
                    // 'no_of_units'=> 'required',
                    'postcode'=> 'required',
                    'primary_manager_id'=> 'required',
                    'regional_manager_id'=> 'required',
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
                $property = new Properties();
                $property->property_name = $r->property_name;
                $property->number_of_units = $r->number_of_units;
                $property->description = $r->description;
                $property->address1 = $r->address1;
                $property->address2 = $r->address2;
                $property->town = $r->town;
                $property->contact_number = $r->contact_number;
                //$property->no_of_units = $r->no_of_units;
                $property->postcode = $r->postcode;
                $property->gps_coordinates = $r->gps_coordinates;
                $property->primary_manager_id = $r->primary_manager_id;
                $property->regional_manager_id = $r->regional_manager_id;

                $property->electric_meter_id = $r->electric_meter_id; 
                $property->gas_meter_id = $r->gas_meter_id; 
                $property->water_meter_id = $r->water_meter_id; 
                $property->solar_panels = $r->solar_panels;

                $property->hmo_license = $r->hmo_license; 
                $property->hmo_license_expiry_date = $r->hmo_license_expiry_date;

                $property->insurance_policy = $r->insurance_policy; 
                $property->insurance_policy_exiry_date = $r->insurance_policy_exiry_date;

                $property->eicr = $r->eicr; 
                $property->eicr_exiry_date = $r->eicr_exiry_date;

                $property->gas_certificate = $r->gas_certificate; 
                $property->gas_certificate_exiry_date = $r->gas_certificate_exiry_date;

                $property->pat_test = $r->pat_test; 
                $property->pat_test_exiry_date = $r->pat_test_exiry_date;

                $property->epc_certificate = $r->epc_certificate; 
                $property->epc_certificate_exiry_date = $r->epc_certificate_exiry_date;

                $property->house_rules = $r->house_rules;

                // uploading all images & videos
                if($r->hasfile('primary_photo'))
                {
                    $primary_image = $r->primary_photo;
                    $filename = FileUploader::uploadFile($primary_image,'properties','primary_photo');
                    $path = url('/').'/storage/properties/'.$filename;
                    $property->primary_photo = json_encode($path);
                }

                if($r->hasfile('images_attachment'))
                {
                    $images_attachment = $r->images_attachment;
                    $images = [];

                    foreach($images_attachment as $id => $img_ath)
                    {
                        $path = '';  $path_data = [];

                        $filename = FileUploader::uploadFile($img_ath,'properties/image_attachment',$id.'_image_attached');
                      
                        $path = url('/').'/storage/properties/image_attachment/'.$filename;
                       
                        $path_data = [
                            'id' => $id,
                            'path'=> $path
                        ];
                        array_push($images,$path_data);

                       
                    }
                    $property->images_attachment = json_encode($images);

                }

                if($r->hasfile('property_video_internal'))
                {
                    $property_video_internal = $r->property_video_internal;
                    $filename = FileUploader::uploadFile($property_video_internal,'properties/internal_video','internal_video');
                    $path = url('/').'/storage/properties/internal_video/'.$filename;
                    $property->property_video_internal = json_encode($path);

                }


                if($r->hasfile('property_video_external'))
                {
                    $property_video_external = $r->property_video_external;
                    $filename = FileUploader::uploadFile($property_video_external,'properties/external_video','external_video');
                    $path = url('/').'/storage/properties/external_video/'.$filename;
                    $property->property_video_external = json_encode($path);

                }


                if($r->hasfile('videos_attachment'))
                {
                    $videos = [];
                    $videos_attachment = $r->videos_attachment;
                    foreach($videos_attachment as $id => $video_ath)
                    {
                        $filename = FileUploader::uploadFile($video_ath,'properties/video_attachment',$id.'_video_attachment');
                        $path = url('/').'/storage/properties/video_attachment/'.$filename;
                        $path_data = [
                            'id' => $id,
                            'path' => $path
                        ];
                        array_push($videos,$path_data);
                        $path_data = '';
                    }
                    $property->videos_attachment = json_encode($videos);

                }
              

                if($property->save())
                {
                    DB::commit();

                    $data = [
                        "message" => "Property added Successfully!",
                        "property" => new PropertyResource($property),
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
                    "message" => "You are unauthorised to create a property!",
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

    // update Property function
    public function updateProperty(Request $r,$id)
    {
        Db::beginTransaction();
       
        try{

            if(Auth::user() && (Auth::user()->role->id == 1 || Auth::user()->role->id == 2))
            {
                $validator = Validator::make($r->all(), [
                    'property_name' => 'required',
                    'number_of_units' => 'required',
                    'description'=>'required',
                    'address1'=>'required',
                    'town'=> 'required',
                    'contact_number'=> 'required',
                    // 'no_of_units'=> 'required',
                    'postcode'=> 'required',
                    'primary_manager_id'=> 'required',
                    'regional_manager_id'=> 'required',
                    // 'electric_meter_id'=> 'required',
                    // 'gas_meter_id'=> 'required',
                    // 'water_meter_id'=> 'required',
                    // 'primary_photo'=> 'required',
                    // 'property_video_internal'=> 'required',
                    // 'property_video_external'=> 'required',
                    // 'house_rules'=> 'required',
                    'primary_photo' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
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


                $property = Properties::find($id);

                if($property)
                {
                    $property->property_name = $r->property_name;
                    $property->number_of_units = $r->number_of_units;
                    $property->description = $r->description;
                    $property->address1 = $r->address1;
                    $property->address2 = $r->address2;
                    $property->town = $r->town;
                    $property->contact_number = $r->contact_number;
                    $property->postcode = $r->postcode;
                    $property->gps_coordinates = $r->gps_coordinates;
    
                    $property->primary_manager_id = $r->primary_manager_id;
                    $property->regional_manager_id = $r->regional_manager_id;
    
    
                    $property->electric_meter_id = $r->electric_meter_id; 
                    $property->gas_meter_id = $r->gas_meter_id; 
                    $property->water_meter_id = $r->water_meter_id; 
                    $property->solar_panels = $r->solar_panels;
    
                    $property->hmo_license = $r->hmo_license; 
                    $property->hmo_license_expiry_date = $r->hmo_license_expiry_date;
    
                    $property->insurance_policy = $r->insurance_policy; 
                    $property->insurance_policy_exiry_date = $r->insurance_policy_exiry_date;
    
                    $property->eicr = $r->eicr; 
                    $property->eicr_exiry_date = $r->eicr_exiry_date;
    
                    $property->gas_certificate = $r->gas_certificate; 
                    $property->gas_certificate_exiry_date = $r->gas_certificate_exiry_date;
    
                    $property->pat_test = $r->pat_test; 
                    $property->pat_test_exiry_date = $r->pat_test_exiry_date;
    
                    $property->epc_certificate = $r->epc_certificate; 
                    $property->epc_certificate_exiry_date = $r->epc_certificate_exiry_date;
    
                    $property->house_rules = $r->house_rules;

                    /*Remove images code */
                    // if($r->has('remove_primary_photo') && ($r->filled('remove_primary_photo')))
                    // {
                    //     $primary_image = explode($property->primary_photo,'/');

                    //     FileUploader::RemoveFile($primary_image,'properties');
                    // }

    
                    // uploading all images & videos
                    if($r->hasfile('primary_photo'))
                    {
                        $primary_image = $r->primary_photo;
                        $filename = FileUploader::uploadFile($primary_image,'properties','primary_photo');
                        $path = url('/').'/storage/properties/'.$filename;
                        $property->primary_photo = json_decode($path);
    
                    }
    
                    if($r->hasfile('images_attachment'))
                    {
                        $images_attachment = $r->images_attachment;
                        $images = [];
    
                        foreach($images_attachment as $img_ath)
                        {
                            $filename = FileUploader::uploadFile($img_ath,'properties/image_attachment','image_attached');
                            $path = url('/').'/storage/properties/image_attachment/'.$filename;
                            array_push($images,$path);
                        }
                        $property->images_attachment = json_encode($images);
    
                    }
    
                    if($r->hasfile('property_video_internal'))
                    {
                        $property_video_internal = $r->property_video_internal;
                        $filename = FileUploader::uploadFile($property_video_internal,'properties/internal_video','internal_video');
                        $path = url('/').'/storage/properties/internal_video/'.$filename;
                        $property->property_video_internal = json_encode($path);
    
                    }
    
    
                    if($r->hasfile('property_video_external'))
                    {
                        $property_video_external = $r->property_video_external;
                        $filename = FileUploader::uploadFile($property_video_external,'properties/external_video','external_video');
                        $path = url('/').'/storage/properties/external_video/'.$filename;
                        $property->property_video_external = json_encode($path);
    
                    }
    
    
                    if($r->hasfile('videos_attachment'))
                    {
                        $videos = [];
                        $videos_attachment = $r->videos_attachment;
                        foreach($videos_attachment as $id => $video_ath)
                        {
                            $filename = FileUploader::uploadFile($video_ath,'properties/video_attachment','video_attachment');
                            $path = url('/').'/storage/properties/video_attachment/'.$filename;
                            $path_data = [
                                'id' => $id,
                                'path' => $path
                            ];
                            array_push($videos,$path_data);
                            $path_data = '';
                        }
                        $property->videos_attachment = json_encode($videos);
    
                    }

                   
                    if($property->save())
                    {
                        DB::commit();

                        $data = [
                            "message" => "Property Updated Successfully!",
                            "property" => new PropertyResource($property),
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


    public function propertylisting()
    {  
        try{
            if(Auth::user() && (Auth::user()->role->id == 1 || Auth::user()->role->id == 2))
            {
                $properties = Properties::paginate(5);

              
                $data = [
                    "message" => "Property Listing",
                    "property" => new PropertyCollection($properties),
                    "error"=>false,  
                ];

                return response()->json($data,200);

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
            return response()->json($e->getMessage(),500); // server error
        }
    }

    public function getpropertybyID($id)
    {
        try{

            if(Auth::user() && (Auth::user()->role->id == 1 || Auth::user()->role->id == 2))
            {
               $property = Properties::find($id);

               if($property)
               {
                    $data = [
                        "message" => "Property Found!",
                        "property" => new PropertyResource($property),
                        "error"=>false,  
                    ];

                    return response()->json($data,200);
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


    public function deleteProperty($id){
        try{

            if(Auth::user() && (Auth::user()->role->id == 1 || Auth::user()->role->id == 2))
            {
               $property = Properties::find($id);
               if($property)
               {

                    if($property->primary_photo)
                    {
                        FileUploader::RemoveFile(json_decode($property->primary_photo));
                    }

                    if($property->images_attachment)
                    {
                        foreach(json_decode($property->images_attachment) as $data)
                        {
                           
                            FileUploader::RemoveFile($data->path);

                        }
                    }

                    if($property->videos_attachment)
                    {
                        foreach(json_decode($property->videos_attachment) as $data)
                        {
                            FileUploader::RemoveFile($data->path);
                        }
                    }


                    if($property->property_video_internal)
                    {
                        FileUploader::RemoveFile(json_decode($property->property_video_internal));
                    }

                    
                    if($property->property_video_external)
                    {
                        FileUploader::RemoveFile(json_decode($property->property_video_external));
                    }


                     $property->delete();
                    $data = [
                        "message" => "Property deleted successfully!",
                        "error"=>false,  
                    ];

                    return response()->json($data,200);
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
    

}
