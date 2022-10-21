<?php

namespace CrankUpIT\Admin\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Contracts\Auth\StatefulGuard;
use CrankUpIT\Admin\Events\AdminRecoveryCodeReplaced;
use Illuminate\Http\Exceptions\HttpResponseException;
use CrankUpIT\Admin\Http\Requests\AdminTFALoginRequest;
use CrankUpIT\Admin\Contracts\AdminTFAChallengeViewResponse;
use CrankUpIT\Admin\Http\Responses\AdminFailedTFALoginResponse;

class AdminTFALoginController extends Controller
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
     * Show the two factor authentication challenge view.
     *
     * @param  \CrankUpIT\Admin\Http\Requests\AdminTFALoginRequest  $request
     * @return \CrankUpIT\Admin\Contracts\AdminTFAChallengeViewResponse
     */
    public function index(AdminTFALoginRequest $request): AdminTFAChallengeViewResponse
    {
        if (!$request->hasChallengedUser()) {
            throw new HttpResponseException(redirect()->route('admin.login'));
        }

        return app(AdminTFAChallengeViewResponse::class);
    }

    /**
     * Attempt to authenticate a new session using the two factor authentication code.
     *
     * @param  \CrankUpIT\Admin\Http\Requests\AdminTFALoginRequest  $request
     * @return mixed
     */
    public function login(AdminTFALoginRequest $request)
    {
        $user = $request->challengedUser();

        if ($code = $request->validRecoveryCode()) {
            $user->replaceRecoveryCode($code);

            event(new AdminRecoveryCodeReplaced($user, $code));
        } elseif (!$request->hasValidCode()) {
            return app(AdminFailedTFALoginResponse::class)->toResponse($request);
        }

        $this->guard->login($user, $request->remember());

        $request->session()->regenerate();

        return app(AdminFailedTFALoginResponse::class);
    }
}
