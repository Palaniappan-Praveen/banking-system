<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_number', 'user_id', 'first_name', 'last_name', 
        'date_of_birth', 'address', 'balance', 'currency', 'is_active',
        'date_of_birth','address' 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function recipientTransactions()
    {
        return $this->hasMany(Transaction::class, 'related_account_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($account) {
            $account->account_number = self::generateAccountNumber();
        });
    }
    //generate unique account number
    public static function generateAccountNumber()
    {
        do {
            $number = mt_rand(1000000000, 9999999999); // 10-digit number
        } while (self::where('account_number', $number)->exists());

        return $number;
    }
}
