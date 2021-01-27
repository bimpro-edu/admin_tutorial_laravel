<?php

return [
    'driver' => env('FCM_PROTOCOL', 'http'),
    'log_enabled' => false,

    'http' => [
        'server_key' => env('FCM_SERVER_KEY', 'AAAAKzMwctE:APA91bGnfZ0lN-6asve9TCCc-VrKjqj0ensaYkXVrNifsltr5za--ld4MJgJzDKBsZwg3uHFqKKL2XedSFwY--KpgfewuFhk--MVI-fLlHhIbrvh0KSPgJzt1XDCvPyP-vk69JrSqaYi'),
        'sender_id' => env('FCM_SENDER_ID', '185542406865'),
        'server_send_url' => 'https://fcm.googleapis.com/fcm/send',
        'server_group_url' => 'https://android.googleapis.com/gcm/notification',
        'timeout' => 60.0, // in second
    ],
];
