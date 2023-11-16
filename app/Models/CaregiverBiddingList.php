<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaregiverBiddingList extends Model
{
    use HasFactory;

    protected $table = 'caregiver_bidding_lists';

    protected $guarded = [];
}
