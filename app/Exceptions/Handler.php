<?php

namespace App\Exceptions;

use Throwable;
use GuzzleHttp\Psr7\Query;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->renderable(function (\Exception $e, $request) {
            if ($request->is('api/*')) {
                if ($e instanceof ModelNotFoundException) {
                    return response()->json([
                        'message' => "Reocrd does not exist in database",
                    ], 404);
                }
                if ($e instanceof QueryException) {
                    return response()->json([
                        'message' => "Something went wrong in Database",
                    ], 422);
                }
                return response()->json([

                    'message' => $e->getMessage(),
                ], 422);
            }
        });
    }
}
