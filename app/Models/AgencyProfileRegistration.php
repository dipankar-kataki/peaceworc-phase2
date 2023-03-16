<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AgencyProfileRegistration extends Model
{
    use HasFactory;

    protected $table = 'agency_profile_registrations';
    protected $guarded = [];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getStatusAttribute($value){
        if($value == 1){
            return 'ACTIVE';
        }else if($value == 2){
            return 'SUSPENDED';
        }else if($value == 3){
            return 'DELETED';
        }
    }

    public function profileCompletionStatus(){
        return $this->hasOne(AgencyInformationStatus::class, 'user_id', 'user_id');
    }

    

}
