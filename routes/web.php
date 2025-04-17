<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OTPController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AccountController;
Auth::routes();

// OTP Routes
Route::get('/otp', [OTPController::class, 'showOTPForm'])->name('otp');
Route::post('/otp/send', [OTPController::class, 'sendOTP'])->name('otp.send');
Route::get('/otp/verify', [OTPController::class, 'showVerifyForm'])->name('otp.verify');
Route::post('/otp/verify', [OTPController::class, 'verifyOTP'])->name('otp.verify.post');

// Home Route
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/accounts/search', [AccountController::class, 'search'])->name('accounts.search');
    Route::resource('accounts', AccountController::class);
});
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('accounts', \App\Http\Controllers\Admin\AccountController::class);
    // Generates these named routes automatically:
    // admin.accounts.index
    // admin.accounts.create
    // admin.accounts.store
     
});
// Transaction Routes
Route::middleware('auth')->group(function () {
    Route::get('/transactions', [\App\Http\Controllers\TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{account}', [\App\Http\Controllers\TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{account}/transfer', [\App\Http\Controllers\TransactionController::class, 'createTransfer'])->name('transactions.transfer');
    Route::post('/transactions/{account}/transfer', [\App\Http\Controllers\TransactionController::class, 'storeTransfer'])->name('transactions.transfer.store');
});

// Currency API Route
Route::get('/update-rates', [\App\Http\Controllers\CurrencyController::class, 'updateRates']);

// Redirect root to home
Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/logout/confirm', function () {
    return view('auth.logout');
})->name('logout.confirm');

// Logout route (POST request for security)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



Route::post('/debug', function(Request $request) {
    dd($request->all()); 
});