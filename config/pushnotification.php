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
        'apiKey' => 'AAAAbynyft0:APA91bHGJkmxRIqPsv1skBapw4-tKe30Ex4FTyD8A4oB8bhNNdYTsUXKGgLfD1pmFFMeukpksuQWGtAFFQeqUOYl_IB_-u2JDoPx-lyaDpYfwJchMWnx1czSEYRLe5x0_WyJOFEQh9Wa',
    ],
    'apn' => [
        'certificate' => __DIR__ . '/iosCertificates/apns-dev-cert.pem',
        'passPhrase' => 'secret', //Optional
        'passFile' => __DIR__ . '/iosCertificates/yourKey.pem', //Optional
        'dry_run' => true,
    ],
];
