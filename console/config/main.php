<?php
$params = require __DIR__ . '/../../common/config/params.php';

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'categories' => ['application'],
                    'logVars' => [],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'categories' => ['yii\queue\Queue'],
                    'logVars' => [],
                    'logFile' => '@runtime/logs/queue.log',
                ],
            ],
        ]
    ],
    'params' => $params,
];
