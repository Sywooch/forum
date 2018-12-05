<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
    'components' => [
        'request' => [
            'cookieValidationKey' => 'zAFUBxwhE7AERYzNSLJ_gqBca059Xh7s',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user'=>[
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['pass/login'],
            'identityCookie' => ['name' => '_identity','httpOnly' => true],
        ],
        'session'=>[
            'name' => '_identitys',
            'class'=>'yii\redis\Session',
            'redis'=>[
                'class'=>\yii\redis\Connection::class,
                'hostname' => '127.0.0.1',
                'port' => 6379,
                'database' =>1,
            ],
        ],
        'log'=>[
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                ['class'=>'yii\rest\UrlRule','controller'=>'user'],
            ],
        ],
    ],
    'params' => $params,
];
