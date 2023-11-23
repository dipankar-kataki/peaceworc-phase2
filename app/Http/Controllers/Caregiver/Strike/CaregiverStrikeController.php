<?php

namespace App\Http\Controllers\Caregiver\Strike;

use App\Http\Controllers\Controller;
use App\Models\Strike;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaregiverStrikeController extends Controller
{
    use ApiResponse;
    public function getActiveStrikes(){
        try{
            $get_active_strikes = Strike::where('user_id', Auth::user()->id)->where('status', 1)->get();
            $strike_details = [];
    
            if(!$get_active_strikes->isEmpty()){
                foreach($get_active_strikes as $strike){
                    $details = [
                        'strike_number' => $strike->strike_number,
                        'strike_reason' => $strike->strike_reason,
                        'start_date_time' => $strike->start_date_time,
                        'lift_date_time' => $strike->end_date_time,
                        'banned_from_bidding' => $strike->banned_from_bidding,
                        'banned_from_quick_call' => $strike->banned_from_quick_call,
                        'rewards_loose' => $strike->rewards_loose,
                    ];
    
                    array_push($strike_details, $details);
                    
                }
    
                return $this->success('Great! Strike details fetched successfully', $strike_details, null, 200);
    
            }else{
                return $this->success('Great! You currently have 0 active STRIKES', null, null, 200);
            }
        }catch(\Exception $e){
            return $this->error('Oops! Something went wrong. Failed to fetched strike details.', null, null, 500);
        }
        
    }
}
