<?php

return array(

    'fivIOS'     => array(
        'environment' =>'development',
        'certificate' => app_path().'/fivmoon/apns-dev-cert.pem',
        'passPhrase'  =>'fivcus',
        'service'     =>'apns'
    ),
     'fivIOSProduction'     => array(
        'environment' =>'production',
        'certificate' => app_path().'/fivmoon/apns-dis-cert.pem',
        'passPhrase'  =>'fivcus',
        'service'     =>'apns'
    ),
    'fivAndroid' => array(
        'environment' =>'production',
        'apiKey'      =>'AIzaSyBonoywsshnew9wbaoLXJKdwhJosonZ9WU',
        'service'     =>'gcm'
    )

);