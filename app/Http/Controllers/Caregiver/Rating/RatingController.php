<?php

namespace App\Http\Controllers\Caregiver\Rating;

use App\Http\Controllers\Controller;
use App\Models\AgencyPostJob;
use App\Models\CaregiverRating;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    use ApiResponse;

    public function getCaregiverRating(){
        try{
            $get_rating = CaregiverRating::where('caregiver_id', Auth::user()->id)->avg('rating');
            return $this->success('Great! Rating Fetched Successfully.', $get_rating, null, 200);
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong.', null, null, 500);
        }
        
    }


    public function addAgencyRating(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'job_id' => 'required',
                'rating' => 'required'
            ]);

            if($validator->fails()){
                return $this->error('Oops! '.$validator->errors()->first(), null, null, 400);
            }else{
                $get_agency_id = AgencyPostJob::where('id', $request->job_id)->first();

                CaregiverRating::create([
                    'caregiver_id' => Auth::user()->id,
                    'agency_id' =>  $get_agency_id->user_id,
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
