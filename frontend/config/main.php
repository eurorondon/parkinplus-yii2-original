<?php

use yii\web\Request;

$baseUrl = str_replace('/frontend/web', '', (new Request())->getBaseUrl());

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'language' => 'es-ES',                  // ✅ guion, no slash
    'name' => 'Parking Plus',
    'basePath' => dirname(__DIR__),
    'homeUrl' => ['site/index'],            // ✅ opción válida (también puedes usar '/')
    'defaultRoute' => 'site/index',         // ✅ por si acceden a /aparcamiento/
    'bootstrap' => ['log'],

    'modules' => [
        'gridview' => [
            'class' => \kartik\grid\Module::class,
        ],
    ],

    // ✅ namespace correcto
    'controllerNamespace' => 'frontend\controllers',

    'components' => [
        'request' => [
            'baseUrl' => $baseUrl,
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                    'logFile' => '@frontend/runtime/logs/app.log',
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                // ✅ regla para la home (necesaria con strictParsing)
                '' => 'site/index',

                // ✅ mapea correctamente con "/"
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',

                // 🔧 HOTFIX opcional para peticiones viejas/marcadores rotos
                'siteindex' => 'site/index',
            ],
        ],
    ],
    'params' => $params,
];
