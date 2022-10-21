<?php

namespace CrankUpIT\Admin;

use CrankUpIT\Admin\Admin;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Cache\Repository;
use CrankUpIT\Admin\Console\InstallCommand;
use Illuminate\Contracts\Auth\StatefulGuard;
use CrankUpIT\Admin\Actions\AdminAttemptToAuth;
use CrankUpIT\Admin\Contracts\AdminTFAProvider;
use CrankUpIT\Admin\Actions\RedirectAdminIfTFAble;
use CrankUpIT\Admin\Http\Responses\AdminLoginResponse;
use CrankUpIT\Admin\Http\Requests\AdminTFALoginRequest;
use CrankUpIT\Admin\Http\Responses\AdminLockoutResponse;
use CrankUpIT\Admin\Http\Controllers\AdminLoginController;
use CrankUpIT\Admin\Http\Controllers\AdminTFALoginController;
use CrankUpIT\Admin\Contracts\AdminLoginResponse as AdminLoginResponseContract;
use CrankUpIT\Admin\ConfirmsAdminPasswords;
use CrankUpIT\Admin\Contracts\AdminLockoutResponse as AdminLockoutResponseContract;


class AdminServicePorvider extends ServiceProvider
{
    /**
     * Boot function of AdminServicePorvider
     *
     * @return void
     */
    public function boot()
    {
        Admin::loginView(function () {
            return view('admin::login');
        });

        Admin::twoFactorChallengeView(function () {
            return view('admin::two-factor-challenge');
        });

        $this->configureRoutes();

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
        // Files that go into the laravel app

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'admin');
    }

    public function register()
    {
        // Flises that stay in the package.
        $this->registerResponseBindings();

        $this->app->singleton(AdminTFAProvider::class, function ($app) {
            return new AdminTFAProvider(
                $app->make(Google2FA::class),
                $app->make(Repository::class)
            );
        });

        /**
         * Provide admin guard to the files where StatefulGuard is needed.
         */
        $this->app->when([
            AdminLoginController::class,
            AdminTFALoginController::class,
            AdminTFALoginRequest::class,
            RedirectAdminIfTFAble::class,
            AdminAttemptToAuth::class,
            ConfirmsAdminPasswords::class,
            ConfirmAdminPassword::class,
        ])->needs(StatefulGuard::class)
            ->give(function () {
                return Auth::guard('admin');
            });
    }

    public function registerResponseBindings()
    {
        $this->app->singleton(
            AdminLoginResponseContract::class,
            AdminLoginResponse::class
        );

        $this->app->singleton(
            AdminLockoutResponseContract::class,
            AdminLockoutResponse::class
        );
    }

    /**
     * Configure the routes offered by the application.
     *
     * @return void
     */
    protected function configureRoutes()
    {
        if (Admin::$registersRoutes) {
            Route::group([
                'namespace' => 'CrankUpIT\Admin\Http\Controllers',
                'domain' => config('admin.domain', null),
                'prefix' => config('admin.prefix'),
            ], function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
            });
        }
    }
}
