<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id', 'amount', 'type', 'currency', 
        'conversion_rate', 'description', 'related_account_id'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function relatedAccount()
    {
        return $this->belongsTo(Account::class, 'related_account_id');
    }
}
