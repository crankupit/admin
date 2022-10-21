<?php

namespace CrankUpIT\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use CrankUpIT\Admin\Actions\ConfirmAdminTFA;

class ConfirmedAdminTFAController extends Controller
{
    /**
     * Enable two factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \CrankUpIT\Admin\Actions\ConfirmAdminTFA  $confirm
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request, ConfirmAdminTFA $confirm)
    {
        $confirm($request->user(), $request->input('code'));

        return $request->wantsJson()
            ? new JsonResponse('', 200)
            : back()->with('status', 'two-factor-authentication-confirmed');
    }
}
