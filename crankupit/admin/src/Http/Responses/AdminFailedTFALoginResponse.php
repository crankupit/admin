<?php

namespace CrankUpIT\Admin\Http\Responses;

use Illuminate\Validation\ValidationException;
use CrankUpIT\Admin\Contracts\AdminFailedTFALoginResponse as ContractsAdminFailedTFALoginResponse;

class AdminFailedTFALoginResponse implements ContractsAdminFailedTFALoginResponse
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        [$key, $message] = $request->has('recovery_code')
            ? ['recovery_code', __('The provided two factor recovery code was invalid.')]
            : ['code', __('The provided two factor authentication code was invalid.')];

        if ($request->wantsJson()) {
            throw ValidationException::withMessages([
                $key => [$message],
            ]);
        }

        return redirect()->route('admin.two-factor.login')->withErrors([$key => $message]);
    }
}
