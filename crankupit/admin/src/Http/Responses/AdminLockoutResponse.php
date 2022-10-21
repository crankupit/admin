<?php

namespace CrankUpIT\Admin\Http\Responses;

use CrankUpIT\Admin\Admin;
use Laravel\Fortify\Fortify;
use Illuminate\Http\Response;
use CrankUpIT\Admin\LoginRateLimiter;
use Illuminate\Validation\ValidationException;
use CrankUpIT\Admin\Contracts\AdminLockoutResponse as AdminLockoutResponseContract;

class AdminLockoutResponse implements AdminLockoutResponseContract
{
    /**
     * The login rate limiter instance.
     *
     * @var \Laravel\Fortify\LoginRateLimiter
     */
    protected $limiter;

    /**
     * Create a new response instance.
     *
     * @param  \Laravel\Fortify\LoginRateLimiter  $limiter
     * @return void
     */
    public function __construct(LoginRateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return with($this->limiter->availableIn($request), function ($seconds) {
            throw ValidationException::withMessages([
                Admin::username() => [
                    trans('auth.throttle', [
                        'seconds' => $seconds,
                        'minutes' => ceil($seconds / 60),
                    ]),
                ],
            ])->status(Response::HTTP_TOO_MANY_REQUESTS);
        });
    }
}
