<?php
$params=require __DIR__ . '/../../common/config/params.php';

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf',
            'cookieValidationKey' => 'zAFUBxwhE7AERYzNSLJ_gqBca059Xh7s',
        ],
        'user'=>[
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['pass/login'],
            'identityCookie' => ['name' => '_identity-web','httpOnly' => true],
        ],
        'session'=>[
            'name' => '_identity-webs',
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
            'showScriptName' => false,
            'suffix'=>'.html',
        ],
    ],
    'params' => $params,
];
