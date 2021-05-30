<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException as TokenMismatchExceptionAlias;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException as HttpExceptionAlias;
use Tymon\JWTAuth\Exceptions\JWTException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Exception $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Exception $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector|\Symfony\Component\HttpFoundation\Response
     * @throws Exception
     */
    public function render($request, Exception $exception)
    {
        switch ($exception) {
            case ($exception instanceof ApiException):
                return $exception->getJsonResponse();
                break;
            case ($exception instanceof AuthenticationException):
                return response()->json([
                    'code' => 401,
                    'message' => 'Token not valid'
                ]);
                break;
            case ($exception instanceof ValidationException):
                return parent::render($request, $exception);
                break;
            case ($exception instanceof TokenMismatchExceptionAlias):
                return redirect($request->fullUrl());
                break;
            case ($exception instanceof JWTException):
                return response()->json([
                    'code' => 401,
                    'message' => 'Token not valid'
                ]);
                break;
            default:
                /** @var $exception Exception */
                if (config('app.debug')) {
                    return parent::render($request, $exception);
                } else {
                    return response()->view('500', [], 500);
                }
        }
    }
}
