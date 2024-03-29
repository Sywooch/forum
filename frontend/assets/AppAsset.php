<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'static/css/uikit.min.css'
    ];
    public $js = [
        'static/js/site.js'
    ];
    public $depends = [
    ];
}
