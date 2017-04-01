<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'firebase' => [
        'api_key' => 'AIzaSyAHnJISxPvaXKeTS_FPIFGiHg59LCyjhE0', // Only used for JS integration
        'auth_domain' => 'report-ed54c.firebaseapp.com', // Only used for JS integration
        'database_url' => 'https://report-ed54c.firebaseio.com',
        'secret' => 'z6UXBuLPyrI9phRDbzOwGdrAirtWwpTqdHjRfkEi',
        'storage_bucket' => 'report-ed54c.appspot.com', // Only used for JS integration
    ]

];
