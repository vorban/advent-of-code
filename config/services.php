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
        'session_cookie' => env('AOC_SESSION_COOKIE'),
    ],

    'github' => [
        'username' => env('GITHUB_USERNAME'),
        'email' => env('GITHUB_EMAIL'),
        'repository' => env('GITHUB_REPOSITORY'),
    ],

];
