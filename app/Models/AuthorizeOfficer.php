<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuthorizeOfficer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'authorize_officers';
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'agency_id', 'id');
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
