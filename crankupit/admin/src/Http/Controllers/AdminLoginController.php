<?php

namespace CrankUpIT\Admin\Http\Controllers;

use CrankUpIT\Admin\Admin;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Auth\StatefulGuard;
use CrankUpIT\Admin\Http\Requests\AdminLoginRequest;
use CrankUpIT\Admin\Contracts\AdminLoginViewResponse;
use CrankUpIT\Admin\Actions\EnsureLoginIsNotThrottled;

class AdminLoginController extends Controller
{
    /**
     * The guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @return void
     */
    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * Show the login view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return CrankUpIT\Admin\Contracts\AdminLoginViewResponse;

     */
    public function index(Request $request): AdminLoginViewResponse
    {
        return app(AdminLoginViewResponse::class);
    }

    /**
     * Attempt to authenticate a new session.
     *
     * @param  \Crankupit\Admin\Http\Requests\AdminLoginRequest  $request
     * @return mixed
     */
    public function login(AdminLoginRequest $request)
    {
        return $this->loginPipeline($request)->then(function ($request) {
            return app(AdminLoginResponse::class);
        });
    }

    /**
     * Get the authentication pipeline instance.
     *
     * @param  \Crankupit\Admin\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Pipeline\Pipeline
     */
    protected function loginPipeline(AdminLoginRequest $request)
    {
        if (Admin::$authenticateThroughCallback) {
            return (new Pipeline(app()))->send($request)->through(array_filter(
                call_user_func(Admin::$authenticateThroughCallback, $request)
            ));
        }

        return (new Pipeline(app()))->send($request)->through(array_filter([
            config('admin.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
            RedirectIfTwoFactorAuthenticatable::class,
            dd($this->guard, $request),
            AttemptToAuthenticate::class,
            dd($this->guard, $request),
            PrepareAuthenticatedSession::class,
            dd($this->guard, $request),
        ]));
    }
}
