<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcceptJob extends Model
{
    use HasFactory;

    protected $table = 'accept_jobs';
    protected $guarded = [];

    public function job(){
        return $this->belongsTo(AgencyPostJob::class, 'job_id', 'id');
    }

    public function user(){
        $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function getStatusAttribute($value){
        if($value == 0){
            return 'Job Not Posted';
        }else if($value == 1){
            return 'Open Job';
        }else if($value == 2){
            return 'Ongoing Job';
        }else if($value == 3){
            return 'Job Completed';
        }else if($value == 4){
            return 'Job Closed';
        }else if($value == 5){
            return 'Pending For Approval';
        }else if($value == 6){
            return 'Bidding Started';
        }else if($value == 7){
            return 'Bidding Ended. Waiting For Results.';
        }else if($value == 8){
            return 'Quick Call';
        }else if($value == 9){
            return 'OnHold.';
        }else if($value == 10){
            return 'Upcoming.';
        }else if($value == 11){
            return 'Cancelled.';
        }
    }
}
