<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW. 
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
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

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (InvalidPlayerPositionException $e, $request) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        });

        $this->renderable(function (InvalidPlayerSkillException $e, $request) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        });
    }

    public function render($request, Throwable $exception)
    {
        
        if ($exception instanceof InvalidPlayerPositionException) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }

        if ($exception instanceof InvalidPlayerSkillException) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        } 

        return parent::render($request, $exception);
    }
}
