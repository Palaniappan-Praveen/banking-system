<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpFoundation\Response;
class Handler extends ExceptionHandler
{

    public function render($request, Throwable $exception)
{
    if ($exception instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException) {
        return response()->view('errors.429', ['message' => 'Too many requests. Please wait a moment.'], Response::HTTP_TOO_MANY_REQUESTS);
    }

    return parent::render($request, $exception);
}
    
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
