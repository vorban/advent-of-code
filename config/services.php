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

    'adventofcode' => [
        'base_uri' => 'https://adventofcode.com',
        'session_cookie' => env('AOC_SESSION_COOKIE'),
    ],

];
