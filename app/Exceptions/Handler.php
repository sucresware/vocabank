<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the throwable types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation throwables.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an throwable.
     *
     * @param \Throwable $throwable
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $throwable)
    {
        if (app()->bound('sentry') && $this->shouldReport($throwable)) {
            app('sentry')->captureException($throwable);
        }

        parent::report($throwable);
    }

    /**
     * Render an throwable into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable               $throwable
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $throwable)
    {
        return parent::render($request, $throwable);
    }
}
