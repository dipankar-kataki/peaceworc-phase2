<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyPayment extends Model
{
    use HasFactory;

    protected $table = 'agency_payments';
    protected $guarded = [];

    public function job(){
        return $this->belongsTo(AgencyPostJob::class, 'job_id', 'id');
    }
}


