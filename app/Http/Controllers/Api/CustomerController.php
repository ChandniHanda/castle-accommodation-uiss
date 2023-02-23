<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\FileAttachmentsTrait as FileUploader;
use App\Traits\CheckUserRoleTrait as CheckRole;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\CustomerCollection;
use App\Models\Customer;
use App\Models\Region;

use Auth;
use Response;
use Validator;
use DB;



class CustomerController extends Controller
{
    //
    public function addCustomer(Request $r){
        try{
            
            Db::beginTransaction();

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

            $validator = Validator::make($r->all(), [
                'customer_id' => 'required',
                'customer_name' => 'required',
                // 'region_id ' => 'required',
                'notes' => 'required',
                'primary_contact_name'=>'required',
                'primary_contact_postiton'=>'required',
                'primary_contact_number'=> 'required',
                'primary_contact_email'=> 'required',
                'billing_contact_name'=> 'required',
                'billing_contact_position'=> 'required',
                'billing_contact_number'=> 'required',
                'billing_contact_email'=> 'required',
                'billing_contact_address_1'=> 'required',
                'billing_contact_address_2'=> 'required',
                'billing_post_code'=> 'required',
                'billing_town'=> 'required',
                'checkin_document_1' => 'required'
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

            $customer = new Customer();
            $customer->customer_id = $r->customer_name;
            $customer->customer_name = $r->customer_name;
            $customer->region_id  = $r->region_id;
            $customer->purchase_order_no  = $r->purchase_order_no;
            $customer->notes = $r->notes;
            $customer->primary_contact_name  = $r->primary_contact_name;
            $customer->primary_contact_postiton  = $r->primary_contact_postiton;

            $customer->primary_contact_number  = $r->primary_contact_number;
            $customer->primary_contact_email  = $r->primary_contact_email;
            $customer->billing_contact_name  = $r->billing_contact_name;
            $customer->billing_contact_position  = $r->billing_contact_position;

            $customer->billing_contact_email  = $r->billing_contact_email;
            $customer->billing_contact_address_1  = $r->billing_contact_address_1;
            $customer->billing_contact_address_2  = $r->billing_contact_address_2;
            $customer->billing_post_code  = $r->billing_post_code;

            $customer->billing_town  = $r->billing_town;
            $customer->checkin_document_1  = $r->checkin_document_1;
            $customer->checkin_document_2  = $r->checkin_document_2;
            $customer->checkin_document_3  = $r->checkin_document_3;
            $customer->checkin_document_4  = $r->checkin_document_4;

            
            if($customer->save())
            {
                DB::commit();

                $data = [
                    "message" => "Property Unit added Successfully!",
                    "property" => new CustomerResource($customer),
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

    public function getCustomerByID($id){
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

        $customer = Customer::find($id);

        if($customer)
        {
            $data = [
                "message" => "Customer Found!",
                "property" => new CustomerResource($customer),
                "error"=>false,  
            ];

            return response()->json($data,200);

        }
        else{

            $data = [
                "message" => "Invalid Customer ID, Customer not found!",
                "errors" => '',
                "error" => true 
            ];
            return response()->json($data,500); // server error

        }

    }


    public function updateCustomer($id,Request $r){
        try{
            
            Db::beginTransaction();

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


            $customer = Customer::find($id);


            if(!$customer)
            {
                $data = [
                    "message" => "Customer ID not found!",
                    "property" => new CustomerResource($customer),
                    "error"=>false,  
                ];
    
                return response()->json($data,200);
    
            }



            $validator = Validator::make($r->all(), [
                'customer_id' => 'required',
                'customer_name' => 'required',
                // 'region_id ' => 'required',
                'notes' => 'required',
                'primary_contact_name'=>'required',
                'primary_contact_postiton'=>'required',
                'primary_contact_number'=> 'required',
                'primary_contact_email'=> 'required',
                'billing_contact_name'=> 'required',
                'billing_contact_position'=> 'required',
                'billing_contact_number'=> 'required',
                'billing_contact_email'=> 'required',
                'billing_contact_address_1'=> 'required',
                'billing_contact_address_2'=> 'required',
                'billing_post_code'=> 'required',
                'billing_town'=> 'required',
                'checkin_document_1' => 'required'
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


            $customer->customer_id = $r->customer_name;
            $customer->customer_name = $r->customer_name;
            $customer->region_id  = $r->region_id;
            $customer->purchase_order_no  = $r->purchase_order_no;
            $customer->notes = $r->notes;
            $customer->primary_contact_name  = $r->primary_contact_name;
            $customer->primary_contact_postiton  = $r->primary_contact_postiton;

            $customer->primary_contact_number  = $r->primary_contact_number;
            $customer->primary_contact_email  = $r->primary_contact_email;
            $customer->billing_contact_name  = $r->billing_contact_name;
            $customer->billing_contact_position  = $r->billing_contact_position;

            $customer->billing_contact_email  = $r->billing_contact_email;
            $customer->billing_contact_address_1  = $r->billing_contact_address_1;
            $customer->billing_contact_address_2  = $r->billing_contact_address_2;
            $customer->billing_post_code  = $r->billing_post_code;

            $customer->billing_town  = $r->billing_town;
            $customer->checkin_document_1  = $r->checkin_document_1;
            $customer->checkin_document_2  = $r->checkin_document_2;
            $customer->checkin_document_3  = $r->checkin_document_3;
            $customer->checkin_document_4  = $r->checkin_document_4;

            
            if($customer->save())
            {
                DB::commit();

                $data = [
                    "message" => "Customer Updated Successfully!",
                    "property" => new CustomerResource($customer),
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

    //Delete Customer Api

    public function deleteCustomer($id)
    {
        try{

            if(Auth::user() && (Auth::user()->role->id == 1 || Auth::user()->role->id == 2))
            {
                $customer = Customer::find($id);
                if($customer)
                {
                    $customer->delete();

                    $data = [
                        "message" => "Customer deleted successfully!",
                        "error"=>false,  
                    ];

                    return response()->json($data,200);
                }else{
                    $data = [
                        "message" => "Invalid Customer ID, Customer not found!",
                        "errors" => '',
                        "error" => true 
                    ];
                    return response()->json($data,500); // server error
                }
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

    //Customer Listing Api

    public function customerListing()
    {  
        try
        {

            $customers = Customer::paginate(5);

            $data = [
                "message" => "Customer Listing",
                "property" => new CustomerCollection($customers),
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

    // Regions API code

    public function addRegions(Request $r)
    {
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

        $regoin = new Region();
        $regoin->name = $r->name;
        
        if($regoin->save())
        {
            $data = [
                "message" => "Region added Successfully!",
                "Region" => $regoin,
                "error"=>false,  
            ];

            return response()->json($data,200);
        }

    }

    public function getRegions(Request $r)
    {

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
        $regions = Region::all();

        $data = [
            "message" => "Regions List",
            "Regions" => $regions,
            "error"=>false,  
        ];

        return response()->json($data,200);


    }
}

