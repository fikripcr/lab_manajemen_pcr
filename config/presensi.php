<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Presensi Settings
    |--------------------------------------------------------------------------
    |
    | Default settings for online presensi system
    |
    */

    'default_latitude' => env('PRESENSI_DEFAULT_LATITUDE', -6.208763),
    'default_longitude' => env('PRESENSI_DEFAULT_LONGITUDE', 106.845599),
    'default_address' => env('PRESENSI_DEFAULT_ADDRESS', 'Jakarta, Indonesia'),
    'default_radius' => env('PRESENSI_DEFAULT_RADIUS', 100), // in meters

    /*
    |--------------------------------------------------------------------------
    | Geocoding Settings
    |--------------------------------------------------------------------------
    |
    | API settings for reverse geocoding
    |
    */

    'geocoding' => [
        'provider' => env('GEOCODING_PROVIDER', 'nominatim'), // nominatim, google, etc.
        'timeout' => env('GEOCODING_TIMEOUT', 10), // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Presensi Rules
    |--------------------------------------------------------------------------
    |
    | Business rules for presensi validation
    |
    */

    'rules' => [
        'min_checkin_time' => env('PRESENSI_MIN_CHECKIN_TIME', '07:00'), // earliest check-in
        'max_checkin_time' => env('PRESENSI_MAX_CHECKIN_TIME', '10:00'), // latest check-in
        'min_checkout_time' => env('PRESENSI_MIN_CHECKOUT_TIME', '16:00'), // earliest check-out
        'max_checkout_time' => env('PRESENSI_MAX_CHECKOUT_TIME', '21:00'), // latest check-out
        'late_threshold_minutes' => env('PRESENSI_LATE_THRESHOLD', 15), // minutes after shift start
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    |
    | Cache configuration for presensi data
    |
    */

    'cache' => [
        'settings_ttl' => env('PRESENSI_CACHE_SETTINGS_TTL', 3600), // 1 hour
        'location_ttl' => env('PRESENSI_CACHE_LOCATION_TTL', 300), // 5 minutes
    ],
];
