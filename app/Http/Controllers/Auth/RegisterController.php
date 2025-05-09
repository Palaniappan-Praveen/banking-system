<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

//event(new Registered($user = $this->create($request->all())));
class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
        //event(new Registered($user = $this->create($request->all())));
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            //'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string'],
            'dob' => ['nullable', 'date'], // Must be 18+ years old
        'address' => ['nullable', 'string'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make('password'),
            'phone' => $data['phone'],
            'role' => 'user',
            'dob' => $data['dob'],
        'address' => $data['address'],
        ]);
    }

    public function showRegistrationForm(Request $request)
    {
        if (!session('phone')) {
            return redirect()->route('otp');
        }

        return view('auth.register');
    }
}