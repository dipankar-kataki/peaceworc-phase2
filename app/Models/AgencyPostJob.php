<?php

namespace App\Models;

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

    public function getExpertiesAttribute($value){
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
            return 'Job Not Posted';
        }else if($value == 1){
            return 'Open Job';
        }else if($value == 2){
            return 'Ongoing Job';
        }else if($value == 3){
            return 'Job Completed';
        }else if($value == 4){
            return 'Job Closed';
        }else if($value == 5){
            return 'Pending For Approval';
        }
    }

}
