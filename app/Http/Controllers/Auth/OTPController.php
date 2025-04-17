<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class OTPController extends Controller
{
    public function showOTPForm()
    {
        return view('auth.otp');
    }

    public function sendOTP(Request $request)
    {
        $request->validate(['phone' => 'required|numeric']);

        $otp = rand(1000, 9999);
        $phone = $request->phone;

        // Store OTP in session for verification
        session(['otp' => $otp, 'phone' => $phone]);

    // Send via SpringEdge API
    $response = Http::post('https://instantalerts.co/api/web/send', [
        'apikey' => env('SPRINGEDGE_API_KEY'),
        'sender' => env('SPRINGEDGE_SENDER_ID'),
        'to' => $phone,
        'message' => "  Mobile Number verification code is ".$otp." Do not share it",
    ]);

    if ($response->successful()) {
        return redirect()->route('otp.verify')->with('success', 'OTP sent!');
    }

    return back()->with('error', 'Failed to send OTP: ' . $response->body());
    }

    public function showVerifyForm()
    {
        return view('auth.verify-otp');
    }

    public function verifyOTP(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        if ($request->otp == Session::get('otp')) {
            // OTP verified, proceed with registration
            Session::forget('otp');
            return redirect()->route('register')->with('phone', Session::get('phone'));
        }

        return back()->with('error', 'Invalid OTP. Please try again.');
    }

    private function sendSMS($phone, $message)
    {
        $apiKey = env('SPRINGEDGE_API_KEY');
        $sender = 'SEDEMO'; // Test sender ID

        return Http::post("https://instantalerts.co/api/web/send", [
            'apikey' => $apiKey,
            'sender' => $sender,
            'to' => $phone,
            'message' => $message,
        ]);
    }
}
