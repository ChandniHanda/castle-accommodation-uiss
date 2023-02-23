<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use Response;
use DB;
use Validator;
use Auth;

class ManagerController extends Controller
{
    public function loginManager(Request $r)
    {
       
        try{
    
            $validator = Validator::make($r->all(), [
                'email' => 'required',
                'password' => 'required',
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

            $credentials = [
                'email' => $r->email,
                'password' => $r->password
            ];
            
            if(Auth::attempt($credentials))
            {
               $user = Auth::user();

               $token = $user->createToken('casteaccomodationToken')->accessToken;

               $data = [
                    "message" => ucfirst(Auth::user()->role->name). ' logged In Successfully!',
                    "user" => new UserResource($user),
                    "token" => $token,
                    "error"=>false,  
                ];

                return response()->json($data,200); 

                
            }
            else{

                
                $data = [
                    "message" => "Invalid Credentials",
                    "errors" => '',
                    "error" => true 
                ];
                return response()->json($data,401); // server error

            }


        }
        catch(Exception $e)
        {
            $data = [
                "message" => "Error Occured",
                "errorsa" => $e->getMessage(),
                "error" => true 
            ];
            return response()->json($data,500); // server error
        }
    }
}
