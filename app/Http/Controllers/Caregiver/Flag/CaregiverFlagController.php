<?php

namespace App\Http\Controllers\Caregiver\Flag;

use App\Http\Controllers\Controller;
use App\Models\CaregiverFlag;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaregiverFlagController extends Controller
{
    use ApiResponse;
    public function getActiveFlags(){
        try{
            $get_active_flags = CaregiverFlag::where('user_id', Auth::user()->id)->where('status', 1)->get();
            $flag_details = [];
    
            if(!$get_active_flags->isEmpty()){
                foreach($get_active_flags as $flag){
                    $details = [
                        'flag_number' => $flag->flag_number,
                        'flag_reason' => $flag->flag_reason,
                        'start_date_time' => $flag->start_date_time,
                        'lift_date_time' => $flag->end_date_time,
                        'banned_from_bidding' => $flag->banned_from_bidding,
                        'banned_from_quick_call' => $flag->banned_from_quick_call,
                        'rewards_loose' => $flag->rewards_loose,
                    ];
    
                    array_push($flag_details, $details);
                    
                }
    
                return $this->success('Great! Flag details fetched successfully', $flag_details, null, 200);
    
            }else{
                return $this->success('Great! You currently have 0 active flags', null, null, 200);
            }
        }catch(\Exception $e){
            return $this->error('Oops! Something went wrong. Failed to fetched flag details.', null, null, 500);
        }
    }
}
