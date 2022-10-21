<?php

namespace CrankUpIT\Admin\Events;

use Illuminate\Foundation\Events\Dispatchable;

class AdminRecoveryCodesGenerated
{
    use Dispatchable;

    /**
     * The user instance.
     *
     * @var \App\Models\Admin
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Admin  $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
