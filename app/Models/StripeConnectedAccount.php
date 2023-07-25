<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripeConnectedAccount extends Model
{
    use HasFactory;

    protected $table = 'stripe_connected_accounts';
    protected $guarded = [];
}
