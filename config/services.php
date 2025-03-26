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

    "postmark" => [
        "token" => env("POSTMARK_TOKEN"),
    ],

    "ses" => [
        "key" => env("AWS_ACCESS_KEY_ID"),
        "secret" => env("AWS_SECRET_ACCESS_KEY"),
        "region" => env("AWS_DEFAULT_REGION", "us-east-1"),
    ],

    "resend" => [
        "key" => env("RESEND_KEY"),
    ],

    "slack" => [
        "notifications" => [
            "bot_user_oauth_token" => env("SLACK_BOT_USER_OAUTH_TOKEN"),
            "channel" => env("SLACK_BOT_USER_DEFAULT_CHANNEL"),
        ],
    ],
    
    "printnode" => [
        "api_key" => env("PRINTNODE_API_KEY", "demo_key"),
        "printer_id" => env("PRINTNODE_PRINTER_ID", "74188357"), // Brother QL-820NWB ID
    ],
    
    "ezeep" => [
        "api_url" => env("EZEEP_API_URL", "https://api.ezeep.com"),
        "client_id" => env("EZEEP_CLIENT_ID"),
        "client_secret" => env("EZEEP_CLIENT_SECRET"),
        "printer_id" => env("EZEEP_PRINTER_ID"),
    ],

];
