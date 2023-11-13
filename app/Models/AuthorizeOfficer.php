<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorizeOfficer extends Model
{
    use HasFactory;

    protected $table = 'authorize_officers';
    protected $guarded = [];

    public function agency(){
        return $this->belongsTo(AgencyProfileRegistration::class, 'agency_id', 'user_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'agency_id', 'id');
    }

    public function getStatusAttribute($value){
        if($value == 1){
            return 'OPEN';
        }else if($value == 2){
            return 'SUSPENDED';
        }else if($value == 3){
            return 'DELETED';
        }
    }

    public function getRoleAttribute($value){
        
        if($value == 4){
            return 'Admin';
        }
        if($value == 5){
            return 'Operator';
        }
    }
}
