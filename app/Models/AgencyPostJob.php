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
}
