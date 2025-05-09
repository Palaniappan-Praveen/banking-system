<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class UserOtp extends Model
{
    //added comments to the userotp model
    //use HasFactory;
    protected $fillable = ['phone', 'otp', 'expires_at'];

    protected $dates = ['expires_at'];

    public function isExpired()
    {
        return $this->expires_at->lt(Carbon::now());
    }
}
