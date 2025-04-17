<?php

namespace App\Console\Commands;

use App\Models\CurrencyRate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateCurrencyRates extends Command
{
    protected $signature = 'currency:update';
    protected $description = 'Update currency exchange rates';

    public function handle()
    {
        $currencies = ['USD', 'GBP', 'EUR'];
        
        foreach ($currencies as $from) {
            $response = Http::get("https://api.exchangerate-api.com/v4/latest/$from");
            
            if ($response->successful()) {
                $data = $response->json();
                
                foreach ($currencies as $to) {
                    if ($from !== $to && isset($data['rates'][$to])) {
                        CurrencyRate::create([
                            'from_currency' => $from,
                            'to_currency' => $to,
                            'rate' => $data['rates'][$to],
                        ]);
                    }
                }
            }
        }

        $this->info('Currency rates updated successfully');
        return 0;
    }
}
