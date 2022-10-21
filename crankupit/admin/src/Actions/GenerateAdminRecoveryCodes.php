<?php

namespace CrankUpIT\Admin\Actions;

use CrankUpIT\Admin\RecoveryCode;
use Illuminate\Support\Collection;
use CrankUpIT\Admin\Events\AdminRecoveryCodesGenerated;

class GenerateAdminRecoveryCodes
{
    /**
     * Generate new recovery codes for the user.
     *
     * @param  mixed  $user
     * @return void
     */
    public function __invoke($user)
    {
        $user->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(8, function () {
                return RecoveryCode::generate();
            })->all())),
        ])->save();

        AdminRecoveryCodesGenerated::dispatch($user);
    }
}
