<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgencyNotification extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'agency_notifications';
    protected $guarded = [];

    public function getTypeAtrribute($value){
        if($value == 1){
            return 'Job';
        }else if($value == 2){
            return 'Payment';
        }else if($value == 3){
            return 'Security';
        }
    }
}
