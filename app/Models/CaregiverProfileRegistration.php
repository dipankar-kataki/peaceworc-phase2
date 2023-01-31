<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaregiverProfileRegistration extends Model
{
    use HasFactory;

    protected $table = 'caregiver_profile_registrations';
    protected $guarded = [];

    public function getDobAttribute($value){
        return Carbon::parse($value)->age;
    }

    public function getCareCompletedAttribute($value){
        if($value == null){
            return 0;
        }
        return Carbon::parse($value)->age;
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
