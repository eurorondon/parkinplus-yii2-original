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
        'css/site.css',
        'css/mobile.css',
        'css/page.css',
        'css/repage.css',
        'css/parking.css',
        'css/style.css',
        'css/tooltips.css',
        'vendor/boxicons/css/boxicons.min.css',
        'vendor/bootstrap-icons/bootstrap-icons.css'
    ];
    public $js = [
        'js/jquery.mask.js',
        'js/main.js',
        'js/app_parking.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        '\rmrevin\yii\fontawesome\AssetBundle',
    ];
    
    public $jsOptions = [
        'async' => true
    ];
    
}
