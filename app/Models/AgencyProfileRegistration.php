<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyProfileRegistration extends Model
{
    use HasFactory;

    protected $table = 'agency_profile_registrations';
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

}
