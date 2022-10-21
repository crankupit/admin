<?php

namespace CrankUpIT\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use CrankUpIT\Admin\Actions\EnableAdminTFA;
use CrankUpIT\Admin\Actions\DisableAdminTFA;

class AdminTFAController extends Controller
{
    /**
     * Enable two factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \CrankUpIT\Admin\Actions\EnableAdminTFA  $enable
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request, EnableAdminTFA $enable)
    {
        $enable($request->user());

        return $request->wantsJson()
            ? new JsonResponse('', 200)
            : back()->with('status', 'two-factor-authentication-enabled');
    }

    /**
     * Disable two factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \CrankUpIT\Admin\Actions\DisableAdminTFA  $disable
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Request $request, DisableAdminTFA $disable)
    {
        $disable($request->user());

        return $request->wantsJson()
            ? new JsonResponse('', 200)
            : back()->with('status', 'two-factor-authentication-disabled');
    }
}
