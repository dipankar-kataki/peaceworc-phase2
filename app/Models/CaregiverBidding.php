<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaregiverBidding extends Model
{
    use HasFactory;

    protected $table = 'caregiver_biddings';
    protected $guarded = [];

    public function job(){
        return $this->belongsTo(AgencyPostJob::class, 'job_id', 'id');
    }
}
