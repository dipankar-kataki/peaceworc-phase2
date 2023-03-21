<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $guarded = [];


    public function agency(){
        return $this->belongsTo(AgencyProfileRegistration::class, 'agency_id', 'user_id');
    }

    public function caregiver(){
        return $this->belongsTo(CaregiverProfileRegistration::class, 'caregiver_id', 'user_id');
    }
}
