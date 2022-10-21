<?php

namespace CrankUpIT\Admin\Actions;

use Illuminate\Auth\Events\Lockout;
use CrankUpIT\Admin\LoginRateLimiter;
use CrankUpIT\Admin\Contracts\AdminLockoutResponse;

class EnsureLoginIsNotThrottled
{
    /**
     * The login rate limiter instance.
     *
     * @var \Laravel\Fortify\LoginRateLimiter
     */
    protected $limiter;

    /**
     * Create a new class instance.
     *
     * @param  \Laravel\Fortify\LoginRateLimiter  $limiter
     * @return void
     */
    public function __construct(LoginRateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        if (!$this->limiter->tooManyAttempts($request)) {
            return $next($request);
        }

        event(new Lockout($request));

        return app(AdminLockoutResponse::class);
    }
}
