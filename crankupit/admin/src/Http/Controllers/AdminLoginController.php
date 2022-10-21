<?php

namespace CrankUpIT\Admin\Http\Controllers;

use CrankUpIT\Admin\Admin;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\StatefulGuard;
use CrankUpIT\Admin\Actions\AdminAttemptToAuth;
use CrankUpIT\Admin\Contracts\AdminLoginResponse;
use CrankUpIT\Admin\Actions\RedirectAdminIfTFAble;
use CrankUpIT\Admin\Actions\PrepareAdminAuthSession;
use CrankUpIT\Admin\Http\Requests\AdminLoginRequest;
use CrankUpIT\Admin\Contracts\AdminLoginViewResponse;
use CrankUpIT\Admin\Actions\EnsureLoginIsNotThrottled;
use CrankUpIT\Admin\Http\Responses\AdminLogoutResponse;
use CrankUpIT\Admin\Contracts\AdminLogoutResponse as AdminLogoutResposeContract;

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
        // dd($this->guard, $request);
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
            // dd($this->guard),
            // RedirectAdminIfTFAble::class,
            // dd($this->guard),
            AdminAttemptToAuth::class,
            // dd($this->guard),
            PrepareAdminAuthSession::class,
            // dd($this->guard),
        ]));
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \CrankUpIT\Admin\Contracts\AdminLogoutResponse
     */
    public function logout(Request $request): AdminLogoutResposeContract
    {
        $this->guard->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return app(AdminLogoutResponse::class);
    }
}
