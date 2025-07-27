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
        'css/siteN.css',
        'css/mobilep.css',
        'css/page.css',
        'css/repage.css',
        'css/parkingN.css',
        'css/styleN.css',
        'css/tooltips.css',
        'vendor/glightbox/css/glightbox.min.css',
        'vendor/boxicons/css/boxicons.min.css',
        'vendor/bootstrap-icons/bootstrap-icons.css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css'
    ];
    public $js = [
        'js/jquery.mask.js',
        'js/main.js',
        'js/app_parking.js',
		'js/counter.js',
        'vendor/glightbox/js/glightbox.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
