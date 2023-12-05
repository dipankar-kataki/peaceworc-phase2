<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaregiverFlag extends Model
{
    use HasFactory;

    protected $table = 'caregiver_flags';

    protected $guarded = [];


    public function getFlagReasonAttribute($value){
        if($value == 1){
            return 'Job not started after accepting.';
        }else if($value == 2){
            return 'Job not accepted after bidding.';
        }
    } 

    public function job(){
        return $this->belongsTo(AgencyPostJob::class, 'job_id', 'id');
    }
}
