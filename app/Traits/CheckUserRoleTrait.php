<?php
namespace App\Traits;

use Auth;

trait CheckUserRoleTrait {

    ##Check for Admin, Manager 
   public Static function checkUserRole(){

        if(Auth::user() && (Auth::user()->role->id == 1 || Auth::user()->role->id == 2))
        {
            return true;
        }
        else{
            return false;

        }
   }
          
    
   ##Check for Admin, Manager & User    
   public static function checkUserAdminRole()
   {
        if(Auth::user() && (Auth::user()->role->id == 1 || Auth::user()->role->id == 2 || Auth::user()->role->id == 3))
        {
            return true;
        }
        else{
            return false;

        }
   }
}
