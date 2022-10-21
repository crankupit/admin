<?php

namespace CrankUpIT\Admin\Http\Responses;

use CrankUpIT\Admin\Admin;
use Illuminate\Http\JsonResponse;
use CrankUpIT\Admin\Contracts\AdminLogoutResponse as AdminLogoutResponseContract;

class AdminLogoutResponse implements AdminLogoutResponseContract
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
            ? new JsonResponse('', 204)
            : redirect(Admin::redirects('logout', 'admin/login'));
    }
}
