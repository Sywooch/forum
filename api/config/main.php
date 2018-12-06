<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace'=>'api\controllers',
    'components' => [
        'request' => [
            'cookieValidationKey' => 'zAFUBxwhE7AERYzNSLJ_gqBca059Xh7s',
            'parsers'=>[
                'application/json'=>'yii\web\JsonParser',
            ]
        ],
        'response'=>[
            'class' => 'yii\web\Response',
            'format'=>'json',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if($response->data!==null&&isset($response->data['status'])){
                    $code=$response->data['status'];
                    $response->data = [
                        'code'=>$code,
                        'message'=>$response->data['message'],
                        'data'=>[],
                    ];
                    $response->statusCode =$code;
                }
            },
        ],
        'user'=>[
            'identityClass' => 'api\models\User',
            'enableSession' =>false,
            'loginUrl'=>null,
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules'=>[
                ['class'=>'yii\rest\UrlRule','controller'=>'user','except'=>['options']],
            ],
        ],
    ],
    'params' => $params,
];
