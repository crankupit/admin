<?php

use App\Providers\RouteServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Routes Prefix / Subdomain
    |--------------------------------------------------------------------------
    |
    | Here you may specify which prefix Fortify will assign to all the routes
    | that it registers with the application. If necessary, you may change
    | subdomain under which all of the Fortify routes will be available.
    |
    */

    'prefix' => 'admin',

    'domain' => null,

    /*
    |--------------------------------------------------------------------------
    | Admin Guard
    |--------------------------------------------------------------------------
    |
    | Here you may specify which authentication guard Admin will use while
    | authenticating users. This value should correspond with one of your
    | guards that is already present in your "auth" configuration file.
    |
    */

    'guard' => 'admin',

    /*
    |--------------------------------------------------------------------------
    | Admin Routes Middleware
    |--------------------------------------------------------------------------
    |
    | Here you may specify which middleware Admin will assign to the routes
    | that it registers with the application. If necessary, you may change
    | these middleware but typically this provided default is preferred.
    |
    */

    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | By default, Admin will throttle logins to 3 requests per 1 minute for
    | every email and IP address combination. However, if you would like to
    | specify a custom rate limiter to call then you may specify it here.
    |
    */

    'limiters' => [
        'login' => '10,1',
        'two-factor' => 'two-factor',
        'verification' => 'verification',
    ],

    /**
     * Returns Admin Dashboard.
     */
    'home' => RouteServiceProvider::ADMIN,

    /*
    |--------------------------------------------------------------------------
    | Username / Email
    |--------------------------------------------------------------------------
    |
    | This value defines which model attribute should be considered as your
    | application's "username" field. Typically, this might be the email
    | address of the users but you are free to change this value here.
    |
    | Out of the box, Fortify expects forgot password and reset password
    | requests to have a field named 'email'. If the application uses
    | another name for the field you may define it below as needed.
    |
    */

    'username' => 'email',

    'email' => 'email',

    /*
    |--------------------------------------------------------------------------
    | Register View Routes
    |--------------------------------------------------------------------------
    |
    | Here you may specify if the routes returning views should be disabled as
    | you may not need them when building your own application. This may be
    | especially true if you're writing a custom single-page application.
    |
    */

    'views' => true,
];
