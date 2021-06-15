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
        'apiKey' => 'AAAA_OePJv8:APA91bFt5pRnXXmfBNdpBmQPcXEiSZAr_r1m4oRBp7koXRvHPTiiruc3ryCpck0caJV7QMXvuqlYIeF7QEOqELvjDXn8nGwJjMKsxlgN2_38JfiAumPAHYm2-XQTvDPChWzaakp0zdbS',
    ],
    'apn' => [
        'certificate' => __DIR__ . '/iosCertificates/apns-dev-cert.pem',
        'passPhrase' => 'secret', //Optional
        'passFile' => __DIR__ . '/iosCertificates/yourKey.pem', //Optional
        'dry_run' => true,
    ],
];
