<?php

namespace CrankUpIT\Admin\Actions;

use CrankUpIT\Admin\RecoveryCode;
use Illuminate\Support\Collection;
use CrankUpIT\Admin\Events\AdminTFAEnabled;
use CrankUpIT\Admin\Contracts\AdminTFAProvider;

class EnableAdminTFA
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
     * Enable two factor authentication for the admin.
     *
     * @param  mixed  $user
     * @return void
     */
    public function __invoke($user)
    {
        $user->forceFill([
            'two_factor_secret' => encrypt($this->provider->generateSecretKey()),
            'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(8, function () {
                return RecoveryCode::generate();
            })->all())),
        ])->save();

        AdminTFAEnabled::dispatch($user);
    }
}
