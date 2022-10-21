<?php

use Illuminate\Support\Facades\Route;
use CrankUpIT\Admin\Http\Controllers\AdminTFAController;
use CrankUpIT\Admin\Http\Controllers\AdminTFQrController;
use CrankUpIT\Admin\Http\Controllers\AdminLoginController;
use CrankUpIT\Admin\Http\Controllers\AdminTFALoginController;
use CrankUpIT\Admin\Http\Controllers\AdminRecoveryCodeController;
use CrankUpIT\Admin\Http\Controllers\AdminTFASecretKeyController;
use CrankUpIT\Admin\Http\Controllers\ConfirmedAdminTFAController;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(
    ['middleware' => config('admin.middleware', ['web'])],
    function () {
        $enableViews = config('admin.views', true);

        // Authentication...
        if ($enableViews) {
            Route::get('/login', [AdminLoginController::class, 'index'])
                ->middleware(['guest:' . config('admin.guard')])
                ->name('get.admin.login');
        }

        $limiter = config('admin.limiters.login');
        $twoFactorLimiter = config('admin.limiters.two-factor');
        // $verificationLimiter = config('fortify.limiters.verification', '6,1');

        Route::post('/login', [AdminLoginController::class, 'login'])
            ->middleware(array_filter([
                'guest:' . config('admin.guard'),
                $limiter ? 'throttle:' . $limiter : null,
            ]))->name('post.admin.login');

        Route::post('/logout', [AdminLoginController::class, 'destroy'])
            ->name('admin.logout');

        // // Password Reset...
        // if (Features::enabled(Features::resetPasswords())) {
        //     if ($enableViews) {
        //         Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        //             ->middleware(['guest:'.config('fortify.guard')])
        //             ->name('password.request');

        //         Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        //             ->middleware(['guest:'.config('fortify.guard')])
        //             ->name('password.reset');
        //     }

        //     Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        //         ->middleware(['guest:'.config('fortify.guard')])
        //         ->name('password.email');

        //     Route::post('/reset-password', [NewPasswordController::class, 'store'])
        //         ->middleware(['guest:'.config('fortify.guard')])
        //         ->name('password.update');
        // }

        // // Registration...
        // if (Features::enabled(Features::registration())) {
        //     if ($enableViews) {
        //         Route::get('/register', [RegisteredUserController::class, 'create'])
        //             ->middleware(['guest:'.config('fortify.guard')])
        //             ->name('register');
        //     }

        //     Route::post('/register', [RegisteredUserController::class, 'store'])
        //         ->middleware(['guest:'.config('fortify.guard')]);
        // }

        // // Email Verification...
        // if (Features::enabled(Features::emailVerification())) {
        //     if ($enableViews) {
        //         Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
        //             ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
        //             ->name('verification.notice');
        //     }

        //     Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        //         ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard'), 'signed', 'throttle:'.$verificationLimiter])
        //         ->name('verification.verify');

        //     Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        //         ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard'), 'throttle:'.$verificationLimiter])
        //         ->name('verification.send');
        // }

        // // Profile Information...
        // if (Features::enabled(Features::updateProfileInformation())) {
        //     Route::put('/user/profile-information', [ProfileInformationController::class, 'update'])
        //         ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
        //         ->name('user-profile-information.update');
        // }

        // // Passwords...
        // if (Features::enabled(Features::updatePasswords())) {
        //     Route::put('/user/password', [PasswordController::class, 'update'])
        //         ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
        //         ->name('user-password.update');
        // }

        // // Password Confirmation...
        // if ($enableViews) {
        //     Route::get('/user/confirm-password', [ConfirmablePasswordController::class, 'show'])
        //         ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')]);
        // }

        // Route::get('/user/confirmed-password-status', [ConfirmedPasswordStatusController::class, 'show'])
        //     ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
        //     ->name('password.confirmation');

        // Route::post('/user/confirm-password', [ConfirmablePasswordController::class, 'store'])
        //     ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
        //     ->name('password.confirm');

        // // Two Factor Authentication...
        // if (Features::enabled(Features::twoFactorAuthentication())) {
        //     if ($enableViews) {
        Route::get('/two-factor-challenge', [AdminTFALoginController::class, 'index'])
            ->middleware(['guest:' . config('admin.guard')])
            ->name('admin.two-factor.login');
        //     }

        Route::post('/two-factor-challenge', [AdminTFALoginController::class, 'login'])
            ->middleware(array_filter([
                'guest:' . config('admin.guard'),
                $twoFactorLimiter ? 'throttle:' . $twoFactorLimiter : null,
            ]));

        // $twoFactorMiddleware = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
        //     ? [config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard'), 'password.confirm']
        //     : [config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')];

        $twoFactorMiddleware = [config('admin.auth_middleware', 'auth') . ':' . config('admin.guard'), 'password.confirm'];


        Route::post('/two-factor-authentication', [AdminTFALoginController::class, 'login'])
            ->middleware($twoFactorMiddleware)
            ->name('admin.two-factor.enable');

        Route::post('/confirmed-two-factor-authentication', [ConfirmedAdminTFAController::class, 'store'])
            ->middleware($twoFactorMiddleware)
            ->name('admin.two-factor.confirm');

        Route::delete('/two-factor-authentication', [AdminTFAController::class, 'destroy'])
            ->middleware($twoFactorMiddleware)
            ->name('admin.two-factor.disable');

        Route::get('/two-factor-qr-code', [AdminTFQrController::class, 'show'])
            ->middleware($twoFactorMiddleware)
            ->name('admin.two-factor.qr-code');

        Route::get('/two-factor-secret-key', [AdminTFASecretKeyController::class, 'show'])
            ->middleware($twoFactorMiddleware)
            ->name('admin.two-factor.secret-key');

        Route::get('/two-factor-recovery-codes', [AdminRecoveryCodeController::class, 'index'])
            ->middleware($twoFactorMiddleware)
            ->name('admin.two-factor.recovery-codes');

        Route::post('/two-factor-recovery-codes', [AdminRecoveryCodeController::class, 'store'])
            ->middleware($twoFactorMiddleware);
    }
    // });
);
