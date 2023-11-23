<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaregiverNotification extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'caregiver_notifications';
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getTypeAttribute($value){
        if($value == 1){
            return 'Job';
        }else if($value == 2){
            return 'Payment';
        }else if($value == 3){
            return 'Security';
        }else if($value == 4){
            return 'Strike';
        }else if($value == 5){
            return 'Flag';
        }
    }

}
