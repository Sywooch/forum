<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'static/css/layui.css',
        'static/css/site.css'
    ];
    public $js = [
        'static/js/site.js'
    ];
    public $depends = [
    ];
}
