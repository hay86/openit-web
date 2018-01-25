<?php

return [
    'access_key_id'     => env('OOS_ACCESS_KEY_ID'),
    'access_key_secret' => env('OOS_ACCESS_KEY_SECRET'),
    'endpoint'          => env('OSS_ENDPOINT'),
    'timeout'           => 10,
    'conn_timeout'      => 10,
    'img_bucket'        => env('OSS_IMG_BUCKET'),
    'img_domain'        => env('OSS_IMG_DOMAIN'),
    'static_domain'     => env('OSS_STATIC_DOMAIN'),
];