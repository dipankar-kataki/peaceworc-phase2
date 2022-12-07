<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyPostJob extends Model
{
    use HasFactory;

    protected $table = 'agency_post_jobs';
    protected $guarded = [];

    public function getCareItemsAttribute($value){
        if($value == 0){
            return [];
        }else{
            return json_decode($value);
        }
    }

    public function getMedicalHistoryAttribute($value){
        if($value == 0){
            return [];
        }else{
            return json_decode($value);
        }
    }

    public function getExpertiesAttribute($value){
        if($value == 0){
            return [];
        }else{
            return json_decode($value);
        }
    }

    public function getOtherRequirementsAttribute($value){
        if($value == 0){
            return [];
        }else{
            return json_decode($value);
        }
    }

    public function getCheckListAttribute($value){
        if($value == 0){
            return [];
        }else{
            return json_decode($value);
        }
    }
}
