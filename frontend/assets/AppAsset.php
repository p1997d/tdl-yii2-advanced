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
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
        'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css',
    ];
    public $js = [
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js',
        'https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js',
        'https://unpkg.com/sortablejs-make/Sortable.min.js',
        'https://cdn.jsdelivr.net/npm/jquery-sortablejs@latest/jquery-sortable.js',
        'https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js',
        'js/main.js',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
