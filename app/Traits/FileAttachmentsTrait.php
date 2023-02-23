<?php
namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait FileAttachmentsTrait {

    //Store Files
    public static function uploadFile($image,$folder=null,$name=null)
    {
        if(strtolower($image->getClientOriginalExtension()) == 'pdf')
        {
            $fileName = $name.'_'.rand(10,5000).time() . '.' . $image->getClientOriginalExtension();
            $store = Storage::disk('local')->put('public/'.$folder.'/PDF'.'/'.$fileName,file_get_contents($image),'public');
        }
        else if(strtolower($image->getClientOriginalExtension()) == 'png' || strtolower($image->getClientOriginalExtension()) == 'jpg' || strtolower($image->getClientOriginalExtension()) == 'jpeg' || strtolower($image->getClientOriginalExtension()) == 'gif' )
        {
            $fileName = $name.'_'.rand(10,5000).time() . '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());
          
            try{
                $img->resize(500, 500, function ($constraint) {
                    $constraint->aspectRatio();
                });
    
            }
            catch(Exception $e)
            {
                $data = [
                    "message" => "Images Error",
                    "errors" => $e->getMessage(),
                    "error" => true 
                ];
                return response()->json($data,500);
            }
            
            $img->stream();
            Storage::disk('local')->put('public/'.$folder.''.'/'.$fileName, $img, 'public');
        }
        else if(strtolower($image->getClientOriginalExtension()) == 'mp4' || strtolower($image->getClientOriginalExtension()) == 'webm')
        {
            $fileName = $name.'_'.rand(10,5000).time() . '.' . $image->getClientOriginalExtension();
            $store = Storage::disk('local')->put('public/'.$folder.'/'.$fileName,file_get_contents($image),'public');


        }

        return $fileName;
    }

    //Remove Files
    public static function RemoveFile($path){

            $new_path = '';
            
            $replace = url('/').'/storage';

            $new_path = str_replace($replace,'',$path);
            
            if (Storage::disk('public')->exists($new_path))
            {
                Storage::disk('public')->delete($new_path);
              
            }
           
        
    }
}
