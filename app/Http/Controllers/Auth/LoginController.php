<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\UserOtp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request){
        $request->validate([
            'phone1' => 'required|string',
            'otp' => 'required|string',
        ]);

        $mobile_no = $request->phone1;
        $otp = $request->otp;

        $userOtp = UserOtp::where('phone', $mobile_no)->first();

        if (!$userOtp) {
            return back()->withErrors(['otp' => 'OTP not found, please request again.']);
        }

        if ($userOtp->isExpired()) {
            return back()->withErrors(['otp' => 'OTP expired, please request again.']);
        }

        if ($userOtp->otp !== $otp) {
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        // OTP valid - log in the user
        $user = User::where('phone', $mobile_no)->first();

        Auth::login($user);

        // Delete OTP after successful login
        $userOtp->delete();
        
        return redirect()->intended('/transactions'); // or login route
        //return view('home');
    }
        
    

    public function loginphone(Request $request){
        $request->validate([
            'phone1' => 'required|string',
        ]);
        $mobile_no = $request->phone1;
        $user = User::where('phone', $mobile_no)->first();
        if (!$user) {
            return back()->withErrors(['phone' => 'Mobile number not registered']);
        }
        // Generate 4-digit OTP
        $otp = rand(1000, 9999);

        // Save OTP in DB with expiry (e.g., 5 minutes)
        UserOtp::updateOrCreate(
            ['phone' => $mobile_no],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(2)
            ]
        );

        // Send via SpringEdge API
    $response = Http::post('https://instantalerts.co/api/web/send', [
        'apikey' => env('SPRINGEDGE_API_KEY'),
        'sender' => env('SPRINGEDGE_SENDER_ID'),
        'to' => $mobile_no,
        'message' => "Mobile Number verification code is ".$otp." Do not share it.",
    ]);


        // TODO: Send OTP via SMS gateway (e.g., Twilio)
        // For demo, we'll just flash OTP (remove in production)
        session()->flash('otp1', $otp);
        session()->flash('phone_no', $mobile_no);
        session()->flash('otp_message',"Dear user OTP is valid for 2 minutes.");
        if ($response->successful()) {
        return redirect()->route('login')->with('phone', $mobile_no);
        }else{
        return back()->with('error', 'Failed to send OTP: ' . $response->body());
        }
    }
}
