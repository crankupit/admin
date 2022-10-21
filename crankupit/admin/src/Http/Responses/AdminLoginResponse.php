<?php

namespace CrankUpIT\Admin\Http\Responses;

use CrankUpIT\Admin\Admin;
use CrankUpIT\Admin\Contracts\AdminLoginResponse as AdminLoginResponseContract;

class AdminLoginResponse implements AdminLoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended(Admin::redirects('admin::get.admin.login'));
    }
}
