<?php

namespace CrankUpIT\Admin\Actions;

use CrankUpIT\Admin\Events\AdminTFADisabled;

class DisableAdminTFA
{
    /**
     * Disable two factor authentication for the admin.
     *
     * @param  mixed  $user
     * @return void
     */
    public function __invoke($user)
    {
        if (
            !is_null($user->two_factor_secret) ||
            !is_null($user->two_factor_recovery_codes) ||
            !is_null($user->two_factor_confirmed_at)
        ) {
            $user->forceFill([
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
            ])->save();
        }

        AdminTFADisabled::dispatch($user);
    }
}
