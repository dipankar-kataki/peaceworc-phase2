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
            return 'NOT POSTED';
        }else if($value == 1){
            return 'OPEN';
        }else if($value == 2){
            return 'ONGOING';
        }else if($value == 3){
            return 'COMPLETED';
        }else if($value == 4){
            return 'CLOSED';
        }else if($value == 5){
            return 'PENDING';
        }else if($value == 6){
            return 'BIDDING STARTED';
        }else if($value == 7){
            return 'BIDDING ENDED';
        }else if($value == 8){
            return 'QUICK CALL';
        }else if($value == 9){
            return 'ON HOLD';
        }else if($value == 10){
            return 'UPCOMING';
        }else if($value == 11){
            return 'CANCELLED';
        }
    }

    public function getStartTimeAttribute($value){
        return Carbon::parse($value)->format('g:i A');
    }

    public function getEndTimeAttribute($value){
        return Carbon::parse($value)->format('g:i A');
    }


}
