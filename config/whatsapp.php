<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Notification Driver
    |--------------------------------------------------------------------------
    | Supported: "log" (writes messages to the Laravel log — default so the
    | system works with no external credentials), "meta" (Meta/Facebook
    | WhatsApp Cloud API) and "twilio" (Twilio WhatsApp API).
    */
    'driver' => env('WHATSAPP_DRIVER', 'log'),

    // Meta WhatsApp Cloud API
    'meta' => [
        'token' => env('WHATSAPP_META_TOKEN'),
        'phone_number_id' => env('WHATSAPP_META_PHONE_ID'),
        'api_version' => env('WHATSAPP_META_VERSION', 'v20.0'),
    ],

    // Twilio WhatsApp
    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'from' => env('TWILIO_WHATSAPP_FROM'), // e.g. whatsapp:+14155238886
    ],
];
