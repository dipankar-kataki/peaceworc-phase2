<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'otp',
        'otp_validity',
        'is_otp_verified',
        'is_agreed_to_terms',
        'fcm_token',
        'lat',
        'long',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'fcm_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getRoleAttribute($value){
        if($value == 3){
            return 'Owner';
        }
    }

    public function agencyProfile(){
        return $this->hasOne(AgencyProfileRegistration::class, 'user_id', 'id');
    }

    public function caregiverProfile(){
        return $this->hasOne(CaregiverProfileRegistration::class,'user_id');
    }

    public function covid(){
        return $this->hasMany(CovidDocument::class)->where('status', 1);
    }

    public function childAbuse(){
        return $this->hasMany(ChildAbuseDocument::class)->where('status', 1);
    }

    public function criminal(){
        return $this->hasMany(CriminalDocument::class)->where('status', 1);
    }

    public function driving(){
        return $this->hasMany(DrivingDocument::class)->where('status', 1);
    }

    public function employment(){
        return $this->hasMany(EmploymentEligibilityDocument::class)->where('status', 1);
    }

    public function identification(){
        return $this->hasMany(IdentificationDocument::class)->where('status', 1);
    }

    public function tuberculosis(){
        return $this->hasMany(TuberculosisDocument::class)->where('status', 1);
    }

    public function w4_form(){
        return $this->hasMany(W4Document::class)->where('status', 1);
    }
}
