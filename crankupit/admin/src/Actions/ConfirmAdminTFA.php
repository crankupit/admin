<?php

namespace CrankUpIT\Admin\Actions;

use CrankUpIT\Admin\Events\AdminTFAConfirmed;
use Illuminate\Validation\ValidationException;
use CrankUpIT\Admin\Contracts\AdminTFAProvider;

class ConfirmAdminTFA
{
    /**
     * The two factor authentication provider.
     *
     * @var \CrankUpIT\Admin\Contracts\AdminTFAProvider
     */
    protected $provider;

    /**
     * Create a new action instance.
     *
     * @param  \CrankUpIT\Admin\Contracts\AdminTFAProvider  $provider
     * @return void
     */
    public function __construct(AdminTFAProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Confirm the two factor authentication configuration for the admin.
     *
     * @param  mixed  $user
     * @param  string  $code
     * @return void
     */
    public function __invoke($user, $code)
    {
        if (
            empty($user->two_factor_secret) ||
            empty($code) ||
            !$this->provider->verify(decrypt($user->two_factor_secret), $code)
        ) {
            throw ValidationException::withMessages([
                'code' => [__('The provided two factor authentication code was invalid.')],
            ])->errorBag('confirmTwoFactorAuthentication');
        }

        $user->forceFill([
            'two_factor_confirmed_at' => now(),
        ])->save();

        AdminTFAConfirmed::dispatch($user);
    }
}
