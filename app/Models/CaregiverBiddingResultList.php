<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaregiverBiddingResultList extends Model
{
    use HasFactory;

    protected $table = 'caregiver_bidding_result_lists';
    protected $guarded = [];

    public function job(){
        return $this->belongsTo(AgencyPostJob::class, 'job_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
