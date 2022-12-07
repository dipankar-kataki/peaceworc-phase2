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
        return json_decode($value);
    }

    public function getMedicalHistoryAttribute($value){
        return json_decode($value);
    }

    public function getExpertiesAttribute($value){
        return json_decode($value);
    }

    public function getOtherRequirementsAttribute($value){
        return json_decode($value);
    }

    public function getCheckListAttribute($value){
        return json_decode($value);
    }
}
