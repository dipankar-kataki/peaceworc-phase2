<?php

namespace App\Http\Controllers\Admin\Agency\Access;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    public function updateStatus(Request $request){
        try{
            
           return response()->json(['message' => 'Great! Status Updated Successfully.', 'data' => $request->all(), 'status' => 1]);
        }catch(\Exception $e){
            return response()->json(['message' => 'Oops! Something Went Wrong.', 'data' => null, 'status' => 0]);
        }
    }
}
