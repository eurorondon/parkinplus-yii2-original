<?php

$smtpHost = getenv('SMTP_HOST') ?: 'mail.parkingplus.es';
$smtpUsername = getenv('SMTP_USERNAME') ?: 'postmaster@parkingplus.es';
$smtpPassword = getenv('SMTP_PASSWORD');
$smtpPort = getenv('SMTP_PORT') ?: '587';
$smtpEncryption = getenv('SMTP_ENCRYPTION') ?: 'tls';

$mailerConfig = [
    'class' => 'yii\swiftmailer\Mailer',
    'viewPath' => '@common/mail',
    'useFileTransport' => false,
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => $smtpHost,
        'username' => $smtpUsername,
        'password' => $smtpPassword !== false ? $smtpPassword : '',
        'port' => $smtpPort,
        'encryption' => $smtpEncryption,
    ],
];

if ($smtpPassword === false || $smtpPassword === '') {
    $mailerConfig['useFileTransport'] = true;
}

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
        ],        
        'formatter' => [
            //'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'php: d-m-Y',
            'datetimeFormat' => 'dd-MM-YYYY HH:mm:ss a',
            'decimalSeparator' => ',',
            'thousandSeparator' => '.',
            'currencyCode' => 'EUR',
            'nullDisplay' => '',
            'numberFormatterOptions' => [
                NumberFormatter::MIN_FRACTION_DIGITS => 2,
                NumberFormatter::MAX_FRACTION_DIGITS => 2,
            ],
            'timeZone' => 'UTC',
            'locale' => 'es'
        ],
        'mailer' => $mailerConfig,
    ],
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu',
            'mainLayout' => '@app/views/layouts/main.php',
        ]
    ],
    /*
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'site/*',
            'servicios/index',
            'precios/index',
            'precios/index',
        ]
    ],*/

];
