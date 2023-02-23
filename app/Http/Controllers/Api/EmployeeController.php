<?php

namespace App\Http\Controllers\Api;
use App\Models\EmployeeGroup;
use App\Models\Employee;
use App\Models\user;
use App\Traits\FileAttachmentsTrait as FileUploader;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\EmployeeCollection;
use Auth;
use Response;
use Validator;
use DB;

class EmployeeController extends Controller
{
    
    // adding employee

    public function addEmployee(Request $r){
        try{

            Db::beginTransaction();

            if(Auth::user() && (Auth::user()->role->id == 1 || Auth::user()->role->id == 2))
            {
               $profile_photo = '';

                $validator = Validator::make($r->all(), [
                    'email' => 'required|unique:users',
                    'name' => 'required',
                    'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                    'card_id' => 'required',
                    'job_title' => 'required',
                    'mobile_number_work' => 'required',
                    'mobile_number_private'=> 'required',
                    'employee_group_id' => 'required',
                    'manager_id' => 'required',
                    'about_me' => 'required',
                    'property_id' => 'required'
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

                if($r->has('photo'))
                {
                   
                    $photo_img = $r->photo;
                    $filename = FileUploader::uploadFile($photo_img,'employee_photos','photo');
                    $path = url('/').'/storage/employee_photos/'.$filename;
                    $profile_photo = json_encode($path);
                   
                }



                // creating user first

                $user = new User();
                $user->first_name = $r->name;
                $user->email = $r->email;
                $user->password = bcrypt('12345678');
                $user->role_id = 3;
                $user->image = $profile_photo;

                if($user->save())
                {
                    // creating employee
                    $employee = new Employee();
                    $employee->user_id = $user->id;
                    $employee->name = $r->name;
                    $employee->email = $r->email;


                    if($r->has('photo'))
                    {
                        $employee->photo = $profile_photo;
                    }

                    $employee->card_id = $r->card_id;
                    $employee->job_title = $r->job_title;
                    $employee->mobile_number_work = $r->mobile_number_work;
                    $employee->mobile_number_private = $r->mobile_number_private;
                    $employee->employee_group_id = $r->employee_group_id;
                    $employee->manager_id = $r->manager_id;
                    $employee->about_me = $r->about_me;
                    $employee->property_id = $r->property_id;

                    if($employee->save())
                    {
                        DB::commit();

                        $data = [
                            "message" => "Employee Updated Successfully!",
                            "property" => new EmployeeResource($employee),
                            "error"=>false,  
                        ];
        
                        return response()->json($data,200);
                    }
                    else {

                        $data = [
                            "message" => "Error Occured while saving data",
                            "errors" => '',
                            "error" => true 
                        ];
                        
                        return response()->json($data,500);
                    }
                }
                else {

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
        catch(Exception $e)
        {
            DB::rollback();
            $data = [
                "data" => '',
                "message" => $e->getMessage(),
                "error" => true
            ];
            return response($e->getMessage(),404);
        }

    }


    ##employee listring

    public function employeeListing(){
        
        try{

            $employees = Employee::paginate(5);

            
            $data = [
                "message" => "Employee Listing",
                "property" => new EmployeeCollection($employees),
                "error"=>false,  
            ];

            return response()->json($data,200);


        }
        catch(Exception $e)
        {
            DB::rollback();

            $data = [
                "data" => '',
                "message" => $e->getMessage(),
                "error" => true
            ];
            return response($e->getMessage(),404);
        }
    }

    public function updateEmployee(Request $r, $id){

        try{

            Db::beginTransaction();

            if(Auth::user() && (Auth::user()->role->id == 1 || Auth::user()->role->id == 2))
            {

                $validator = Validator::make($r->all(), [
                    'name' => 'required',
                    'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                    'card_id' => 'required',
                    'job_title' => 'required',
                    'mobile_number_work' => 'required',
                    'mobile_number_private'=> 'required',
                    'employee_group_id' => 'required',
                    'manager_id' => 'required',
                    'about_me' => 'required',
                    'property_id' => 'required'
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

                $employee = Employee::find($id);

                if($employee)
                {
                    $employee->name = $r->name;


                    if($r->has('photo'))
                    {
                        $photo_img = $r->photo;
                        $filename = FileUploader::uploadFile($photo_img,'employee_photos','photo');
                        $path = url('/').'/storage/employee_photos/'.$filename;
                        $employee->photo = json_encode($path);
                    
                    }

                    $employee->card_id = $r->card_id;
                    $employee->job_title = $r->job_title;
                    $employee->mobile_number_work = $r->mobile_number_work;
                    $employee->mobile_number_private = $r->mobile_number_private;
                    $employee->employee_group_id = $r->employee_group_id;
                    $employee->manager_id = $r->manager_id;
                    $employee->about_me = $r->about_me;
                    $employee->property_id = $r->property_id;


                    if($employee->save())
                    {
                        DB::commit();

                        $data = [
                            "message" => "Employee Updated Successfully!",
                            "property" => new EmployeeResource($employee),
                            "error"=>false,  
                        ];
        
                        return response()->json($data,200);
                    }
                    else {

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
                        "message" => "Invalid Employee ID, Employee not found!",
                        "errors" => '',
                        "error" => true 
                    ];
                    return response()->json($data,500); // server error
                }


            }
            else 
            {
                $data = [
                    "message" => "You are unauthorised to create a property!",
                    "errors" => '',
                    "error" => true 
                ];
                return response()->json($data,500);
            }
            
        }
        catch(Exception $e)
        {
            DB::rollback();
            $data = [
                "data" => '',
                "message" => $e->getMessage(),
                "error" => true
            ];
            return response($e->getMessage(),404);
        }



    }


    public function deleteEmployee($id)
    {
        try{

            if(Auth::user() && (Auth::user()->role->id == 1 || Auth::user()->role->id == 2))
            {
               $employee = Employee::find($id);
               if($employee)
               {
                    if($employee->photo)
                    {
                        FileUploader::RemoveFile(json_decode($employee->photo));
                    }

                    // deleting record from employee table
                    User::find($employee->user_id)->delete();

                    $employee->delete();

                    $data = [
                        "message" => "Employee deleted successfully!",
                        "error"=>false,  
                    ];

                    return response()->json($data,200);
               }
               else{

                $data = [
                    "message" => "Invalid Employee ID, Employee not found!",
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




    ### Employee Group Functions ###

    public function addEmployeeGroup(Request $r)
    {
        try{

            Db::beginTransaction();

            if(Auth::user() && (Auth::user()->role->id == 1 || Auth::user()->role->id == 2))
            {
                
                $validator = Validator::make($r->all(), [
                    'name' => 'required',
                ]);

                $emp_group = new EmployeeGroup();
                $emp_group->name = $r->name;

                if($emp_group->save())
                {
                    DB::commit();

                    $data = [
                        "message" => "Employee Group Added",
                        "property" => $emp_group,
                        "error"=>false,  
                    ];
    
                    return response()->json($data,200);
    
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
            


        }
        catch(Exception $e)
        {
            DB::rollback();

            $data = [
                "data" => '',
                "message" => $e->getMessage(),
                "error" => true
            ];
            return response($e->getMessage(),404);
        }
    }

    public function employeeGrouplisting()
    {
        try{

            $employeegroups = EmployeeGroup::all();

            $data = [
                "message" => "Employee Groups",
                "property" => $employeegroups,
                "error"=>false,  
            ];

            return response()->json($data,200);

        }
        catch(Exception $e)
        {
            $data = [
                "data" => '',
                "message" => $e->getMessage(),
                "error" => true
            ];
            return response($e->getMessage(),404);
        }

    }


    






}
