<?php

namespace CrankUpIT\Admin;

use CrankUpIT\Admin\Contracts\AdminLoginViewResponse;
use CrankUpIT\Admin\Http\Responses\AdminViewResponse;
use CrankUpIT\Admin\Contracts\AdminTFAChallengeViewResponse;

class Admin
{
    /**
     * The callback that is responsible for building the authentication pipeline array, if applicable.
     *
     * @var callable|null
     */
    public static $authenticateThroughCallback;

    /**
     * The callback that is responsible for validating authentication credentials, if applicable.
     *
     * @var callable|null
     */
    public static $authenticateUsingCallback;

    /**
     * Indicates if Fortify routes will be registered.
     *
     * @var bool
     */
    public static $registersRoutes = true;

    /**
     * Get a completion redirect path for a specific feature.
     *
     * @param  string  $redirect
     * @return string
     */
    public static function redirects(string $redirect, $default = null)
    {
        return config('admin.redirects.' . $redirect) ?? $default ?? config('admin.home');
    }

    /**
     * Register the views for Fortify using conventional names under the given namespace.
     *
     * @param  string  $namespace
     * @return void
     */
    public static function viewNamespace(string $namespace)
    {
        static::viewPrefix($namespace . '::');
    }

    /**
     * Register the views for Fortify using conventional names under the given prefix.
     *
     * @param  string  $prefix
     * @return void
     */
    public static function viewPrefix(string $prefix)
    {
        static::loginView($prefix . 'login');
        static::twoFactorChallengeView($prefix . 'two-factor-challenge');
        // static::registerView($prefix . 'register');
        // static::requestPasswordResetLinkView($prefix . 'forgot-password');
        // static::resetPasswordView($prefix . 'reset-password');
        // static::verifyEmailView($prefix . 'verify-email');
        // static::confirmPasswordView($prefix . 'confirm-password');
    }

    /**
     * Specify which view should be used as the login view.
     *
     * @param  callable|string  $view
     * @return void
     */
    public static function loginView($view)
    {
        app()->singleton(AdminLoginViewResponse::class, function () use ($view) {
            return new AdminViewResponse($view);
        });
    }

    /**
     * Specify which view should be used as the two factor authentication challenge view.
     *
     * @param  callable|string  $view
     * @return void
     */
    public static function twoFactorChallengeView($view)
    {
        app()->singleton(AdminTFAChallengeViewResponse::class, function () use ($view) {
            return new AdminViewResponse($view);
        });
    }

    /**
     * Get the username used for authentication.
     *
     * @return string
     */
    public static function username()
    {
        return config('admin.username', 'email');
    }

    /**
     * Get the name of the email address request variable / field.
     *
     * @return string
     */
    public static function email()
    {
        return config('admin.email', 'email');
    }
}
