<?php

namespace App\Http\Controllers\Agency\Rating;

use App\Http\Controllers\Controller;
use App\Models\AgencyRating;
use App\Models\CaregiverRating;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{   
    use ApiResponse;

    public function getAgencyRating(){
        try{
            $get_rating = CaregiverRating::where('agency_id', Auth::user()->id)->avg('rating');
            return $this->success('Great! Rating Fetched Successfully.', $get_rating, null, 200);
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong.', null, null, 500);
        }
    }

    public function addAgencyRating(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'caregiver_id' => 'required',
                'rating' => 'required'
            ]);

            if($validator->fails()){
                return $this->error('Oops! '.$validator->errors()->first(), null, null, 400);
            }else{
                
                AgencyRating::create([
                    'caregiver_id' => $request->caregiver_id,
                    'agency_id' =>  Auth::user()->id,
                    'review' => $request->review,
                    'rating' => $request->rating
                ]);

                return $this->success('Great! Rating Submitted Successfully', null, null, 200);
            }
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong.', null, null, 500);
        }
    }
}
