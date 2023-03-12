<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Reward;

class CaregiverProfileRegistration extends Model
{
    use HasFactory;

    protected $table = 'caregiver_profile_registrations';
    protected $guarded = [];

    public function getDobAttribute($value){
        return Carbon::parse($value)->format('m-d-Y');
    }

    public function getCareCompletedAttribute($value){
        if($value == null){
            return 0;
        }

        return $value;
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
