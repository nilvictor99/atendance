<?php

use Filament\Support\Colors\Color;

return [

    /*
    |--------------------------------------------------------------------------
    | Super User Configuration
    |--------------------------------------------------------------------------
    |
    | This section defines the configuration for the super user of the system.
    | You can set the name, ID, email, and initial company details here.
    |
    */

    'user' => [
        'name' => env('NAME_SUPER_USER'),
        'id' => env('ID_SUPER_USER'),
        'email' => env('EMAIL_SUPERUSER'),
        'password' => env('PASSWORD_SUPERUSER'),
    ],

    'settings' => [
        'logo_white' => '/system/logos/white.webp',
        'logo_black' => '/system/logos/black.webp',
        'favicon-white' => '/system/favicons/favicon.ico',
        'primary_color' => env('PRIMARY_COLOR', Color::Indigo),
    ],

];
