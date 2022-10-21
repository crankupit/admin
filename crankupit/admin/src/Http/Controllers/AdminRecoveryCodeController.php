<?php

namespace CrankUpIT\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use CrankUpIT\Admin\Actions\GenerateAdminRecoveryCodes;

class AdminRecoveryCodeController extends Controller
{
    /**
     * Get the two factor authentication recovery codes for authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (
            !$request->user()->two_factor_secret ||
            !$request->user()->two_factor_recovery_codes
        ) {
            return [];
        }

        return response()->json(json_decode(decrypt(
            $request->user()->two_factor_recovery_codes
        ), true));
    }

    /**
     * Generate a fresh set of two factor authentication recovery codes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \CrankUpIT\Admin\Actions\GenerateAdminRecoveryCodes  $generate
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, GenerateAdminRecoveryCodes $generate)
    {
        $generate($request->user());

        return $request->wantsJson()
            ? new JsonResponse('', 200)
            : back()->with('status', 'recovery-codes-generated');
    }
}
