<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ManagerController;
use App\Http\Controllers\Api\PropertiesController;
use App\Http\Controllers\Api\PropertyUnitsController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\OccupancyController;
use App\Http\Controllers\Api\IncidentsController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/createUser',[UserController::class,'createUser']);


Route::middleware('auth:api')->group(function () {


    // manager/admin routes
    Route::group(['prefix'=>'manager'], function(){

        Route::controller(ManagerController::class)->group(function () {
        });

    });


    // employee routes
    Route::group(['prefix'=>'employee'], function(){
        Route::controller(EmployeeController::class)->group(function () {
            Route::post('/add', 'addEmployee');
            Route::get('/listing','employeeListing');
            Route::post('/update/{id}','updateEmployee');
            Route::delete('/delete/{id}','deleteEmployee');
           
            ### employeegroup routes ###
            Route::post('/addemployeegroup', 'addEmployeeGroup');
            Route::get('/getemployeegroups', 'employeeGrouplisting'); 
        });
    });


    // Property Routes
    Route::group(['prefix'=>'property'], function(){
        Route::controller(PropertiesController::class)->group(function () {
            Route::post('/add', 'addProperty');
            Route::post('/update/{id}', 'updateProperty');
            Route::get('listing', 'propertylisting');
            Route::get('propertybyid/{id}', 'getpropertybyID');
            Route::delete('/delete/{id}','deleteProperty');

        });
    });

    // Property Units
    Route::group(['prefix'=>'propertyunits'], function(){
        Route::controller(PropertyUnitsController::class)->group(function () {
            Route::post('/add', 'addPropertyUnit');
            Route::post('/update/{id}', 'updatePropertyUnit');
            Route::get('/listing', 'propertyListing');
            Route::get('/getPropertyUnitbyPID/{id}', 'getProperyUnitByPID');
            Route::get('/getPropertyUnit/{id}','getpropertyUnitbyID');
        });
    });

    // Customer Routes
     // manager/admin routes
     Route::group(['prefix'=>'customer'], function(){

        Route::controller(CustomerController::class)->group(function () {

            Route::post('/add','addCustomer');
            Route::get('/getCustomerByID/{id}','getCustomerByID');
            Route::post('/updateCustomer/{id}','updateCustomer');
            Route::delete('/delete/{id}','deleteCustomer');
            Route::get('/listing','customerListing');
            
            // Region Customers
            Route::post('/addRegion','addRegions');
            Route::get('/regions','getRegions');

        });

    });

    //Incidents routes
    Route::group(['prefix'=>'incident'], function(){

        Route::controller(IncidentsController::class)->group(function () {

            Route::post('/add','addIncident');

        });

    });

    // Occupancy Controller
    // CheckIn
    Route::group(['prefix'=>'checkin'], function(){
        Route::controller(OccupancyController::class)->group(function () {
            Route::post('/add','createCheckIn');
        });
    });

    // Checkout
    Route::group(['prefix'=>'checkout'], function(){
        Route::controller(OccupancyController::class)->group(function () {
            Route::post('/add','createCheckOut');
        });
    });


    
});


##Routes without middleware
// manager login 
Route::post('/manager/login',[ManagerController::class,'loginManager']);

