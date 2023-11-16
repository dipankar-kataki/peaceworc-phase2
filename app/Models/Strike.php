<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Strike extends Model
{
    use HasFactory;

    protected $table ='strikes';
    protected $guarded = [];

    public function getStrikeReasonAttribute($value){
        if($value == 1){
            return 'Job not started after accepting.';
        }else if($value == 2){
            return 'Job not accepted after bidding.';
        }
    }
}
