<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'phpmailer' => [
        'host' => env('smtp.yandex.ru'),
        'domain' => env('fp-nk.ru'),
        'secure' => env('ssl'),
        'mailer' => env('smtp'),
        'port' => env('465'),
        'username' => env('support4@siteedit.ru'),
        'password' => env('34index53'),
        'mailto' => env('support4@siteedit.ru'),
    ],

    'recaptcha' => [
        'key' => env('6Lem230eAAAAAK2B9JpW6AUyEthjovQ0lEPcFOCC'),
        'secret' => env('6Lem230eAAAAANE19p9knkjfoRG-3fJcv4v16M-2'),
    ],

];
