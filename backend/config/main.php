<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/params.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'defaultRoute'=>'home/index',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf',
        ],
        'user' => [
            'identityClass' => 'backend\models\User',
            'enableAutoLogin' =>true,
            'loginUrl' => ['login/index'],
            'identityCookie' => ['name' => '_identity-back', 'httpOnly' => true],
        ],
        'session' => [
            'name' => 'advanced-back',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'authManager'=>[
            'class' => 'yii\rbac\DbManager',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
    ],
    'params' => $params,
];
