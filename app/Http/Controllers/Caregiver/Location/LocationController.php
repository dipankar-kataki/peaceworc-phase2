<?php

namespace App\Http\Controllers\Caregiver\Location;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    use ApiResponse;
    public function updateCurrentLocation(Request $request){
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'long' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Oops! Validation Failed. '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                $update = User::where('id', Auth::user()->id)->update([
                    'lat' => $request->lat,
                    'long' => $request->long
                ]);

                if($update){
                    return $this->success('Great! Live Location Updated', null, null, 200);
                }else{
                    return $this->error('Oops! Failed To Update Live Location.', null, null, 500);
                }
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong', null, null, 500);
            }
        }
    }
}
