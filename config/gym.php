<?php

return [

    'name' => 'ChampionClub',

    'tagline' => 'Strength & Conditioning Gym',

    'phone' => env('GYM_PHONE', '8700362410'),

    'whatsapp' => env('GYM_WHATSAPP', env('GYM_PHONE', '8700362410')),

    'country_code' => '91',

    'whatsapp_message' => 'Hi, I want to know about your membership plans',

    'email' => env('GYM_EMAIL', 'hello@championclub.in'),

    'address' => [
        'street' => '12 Strength Avenue, Sector 21',
        'locality' => 'Gurugram',
        'region' => 'Haryana',
        'country' => 'IN',
    ],

    'hours' => [
        ['days' => 'Mon – Sat', 'time' => '6:00 AM – 10:00 PM', 'schema' => 'Mo-Sa 06:00-22:00', 'from' => 'Mo', 'to' => 'Sa', 'opens' => '06:00', 'closes' => '22:00'],
        ['days' => 'Sunday', 'time' => '7:00 AM – 1:00 PM', 'schema' => 'Su 07:00-13:00', 'from' => 'Su', 'to' => 'Su', 'opens' => '07:00', 'closes' => '13:00'],
    ],

    'social' => [
        'instagram' => env('GYM_INSTAGRAM'),
        'facebook' => env('GYM_FACEBOOK'),
        'youtube' => env('GYM_YOUTUBE'),
    ],

    'highlights' => [
        ['value' => 20000, 'suffix' => '', 'label' => 'Sq. ft. training floor', 'icon' => 'area'],
        ['value' => 12, 'suffix' => '', 'label' => 'Certified coaches', 'icon' => 'medal'],
        ['value' => 500, 'suffix' => '+', 'label' => 'Members training', 'icon' => 'users'],
        ['value' => 7, 'suffix' => '', 'label' => 'Days a week open', 'icon' => 'calendar'],
    ],

    'years_running' => 8,

    'about_title' => 'Coached, not just supervised.',

    'about_story' => null,

    'about_images' => [
        'main' => null,
    ],

];
