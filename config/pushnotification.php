<?php
/**
 * @see https://github.com/Edujugon/PushNotification
 */

return [
    'gcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'My_ApiKey',
    ],
    'fcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'AAAACKXdzhU:APA91bFzmNuYiQaoLn5zZqqJoAi3aVihcrIyEYqN2CJfUEKB89MDXXCVZ8j5o8wyNJQeaex4pJklzQfLIySBYJB2WIzqCzq42pnsN3ocsFxGkHP_-KaqT_SRhKIo0Ll3px4s8hD6LJzx',
    ],
    'apn' => [
        'certificate' => __DIR__ . '/iosCertificates/apns-dev-cert.pem',
        'passPhrase' => 'secret', //Optional
        'passFile' => __DIR__ . '/iosCertificates/yourKey.pem', //Optional
        'dry_run' => true,
    ],
];
