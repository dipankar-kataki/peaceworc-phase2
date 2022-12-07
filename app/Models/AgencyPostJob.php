<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyPostJob extends Model
{
    use HasFactory;

    protected $table = 'agency_post_jobs';
    protected $guarded = [];

    public function getCareItemsAttribute($value){
        return json_decode($value);
    }
}
