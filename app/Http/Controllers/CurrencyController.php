<?php

namespace App\Http\Controllers;

use App\Models\CurrencyRate;
use Illuminate\Support\Facades\Http;

class CurrencyController extends Controller
{
    public function updateRates()
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

        return response()->json(['message' => 'Currency rates updated successfully']);
    }
}
