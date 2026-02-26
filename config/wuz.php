<?php

return [
    'api_url' => env('WUZ_API_URL', 'http://localhost:8080'),
    'admin_token' => env('WUZ_ADMIN_TOKEN', 'token'),
    'user_token' => env('WUZ_USER_TOKEN', 'token'),
    'download_media' => env('WUZ_DOWNLOAD_MEDIA', false),
];
