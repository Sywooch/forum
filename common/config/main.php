<?php
return [
    'aliases'=>['@bower' => '@vendor/bower-asset'],
    'vendorPath'=>dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap'=>['queue'],
    'language'=>'zh-CN',
    'components'=>[
        'db' =>[
            'class'=>'yii\db\Connection',
            'dsn'=>'mysql:host=127.0.0.1;dbname=bbs',
            'username'=>'root',
            'password'=>'',
            'charset'=>'utf8',
            'tablePrefix'=>'bbs_',
        ],
        'mailer'=>[
            'class'=>'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.qq.com',
                'username' => '2692772800@qq.com',
                'password' => 'eqwcacxmksazdfhc',
                'port' => '465',
                'encryption' => 'ssl',
            ],
        ],
        'cache'=>[
            'class'=>'yii\redis\Cache',
            'redis'=>[
                'class'=>\yii\redis\Connection::class,
                'hostname'=>'127.0.0.1',
                'port'=>6379,
                'database'=>2,
            ],
        ],
        'redis'=>[
            'class'=>\yii\redis\Connection::class,
            'hostname'=>'127.0.0.1',
            'port'=>6379,
            'database'=>0,
        ],
        'queue'=>[
            'class' => \yii\queue\amqp_interop\Queue::class,
            'port' => 5672,
            'user' => 'guest',
            'password' => 'guest',
            'queueName' => 'queue',
            'driver' => yii\queue\amqp_interop\Queue::ENQUEUE_AMQP_LIB,
        ],
        'elasticsearch'=>[
            'class' => 'yii\elasticsearch\Connection',
            'autodetectCluster'=>false,
            'nodes' => [['http_address' => '127.0.0.1:9200']],
        ],
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => ['app' => 'app.php'],
                ],
                'yii'=>[
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => ['yii' => 'yii.php'],
                ]
            ],
        ]
    ],
];
