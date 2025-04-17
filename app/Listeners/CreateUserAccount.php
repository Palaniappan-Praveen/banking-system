<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Models\Account;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateUserAccount
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        Account::create([
        'user_id' => $event->user->id,
        'account_number' => Account::generateAccountNumber(),
        'first_name' => explode(' ', $event->user->name)[0], // First part of name
        'last_name' => explode(' ', $event->user->name)[1] ?? '', // Last name if exists
        'date_of_birth' => $event->user->dob, // From registration
        'address' => $event->user->address, // From registration
        'balance' => 10000.00,
        'currency' => 'USD',
        'is_active' => true,
    ]);
    }
}
