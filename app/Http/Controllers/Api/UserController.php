<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use Response;
use DB;
use Validator;



class UserController extends Controller
{
    public function createUser(Request $r)
    {
        try{
            Db::beginTransaction();

            $validator = Validator::make($r->all(), [
                'email' => 'required|unique:users',
                'role_id' => 'required',
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
        

            $user = new User();
            $user->first_name = $r->first_name;
            $user->last_name = $r->last_name;
            $user->role_id = $r->role_id;
            $user->email = $r->email;
            $user->password = bcrypt($r->password);
            
            if($user->save())
            {
                DB::commit();

                $token = $user->createToken('casteaccomodationToken')->accessToken;
            }

            $data = [
                "message" => "User Details",
                "user" => new UserResource($user),
                "token" => $token,
                "error"=>false,  
            ];

            return response()->json($data,200);

        }
        catch(Exception $e)
        {
            DB::rollback();
            
            $data = [
                "message" => "Error Occured",
                "errors" => $e->getMessage(),
                "error" => true 
            ];
            return response()->json($data,500); // server error


        }
        
    }
}
