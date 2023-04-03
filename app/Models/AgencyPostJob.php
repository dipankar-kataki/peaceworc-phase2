<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgencyPostJob extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'agency_post_jobs';
    protected $guarded = [];

    // public function getDateAttribute($value){
    //     return date_create($value)->format('m-d-Y');
    // }

    public function getCareItemsAttribute($value){
        if(empty(json_decode($value))){
            return [];
        }else{
            return json_decode($value);
        }
    }

    public function getMedicalHistoryAttribute($value){
        if(empty(json_decode($value))){
            return [];
        }else{
            return json_decode($value);
        }
    }

    public function getExpertiseAttribute($value){
        if(empty(json_decode($value))){
            return [];
        }else{
            return json_decode($value);
        }
    }

    public function getOtherRequirementsAttribute($value){
        if(empty(json_decode($value))){
            return [];
        }else{
            return json_decode($value);
        }
    }

    public function getCheckListAttribute($value){
        if(empty(json_decode($value))){
            return [];
        }else{
            return json_decode($value);
        }
    }

    public function getStatusAttribute($value){
        if($value == 0){
            return 'Not Posted';
        }else if($value == 1){
            return 'Open Job';
        }else if($value == 2){
            return 'Ongoing Job';
        }else if($value == 3){
            return 'Completed Job';
        }else if($value == 4){
            return 'Closed';
        }else if($value == 5){
            return 'Pending';
        }else if($value == 6){
            return 'Bidding Started';
        }else if($value == 7){
            return 'Bidding Ended';
        }else if($value == 8){
            return 'Quick Call';
        }else if($value == 9){
            return 'On Hold';
        }else if($value == 10){
            return 'Upcoming';
        }else if($value == 11){
            return 'Cancelled';
        }
    }

    public function getStartTimeAttribute($value){
        return Carbon::parse($value)->format('g:i A');
    }

    public function getEndTimeAttribute($value){
        return Carbon::parse($value)->format('g:i A');
    }


}
