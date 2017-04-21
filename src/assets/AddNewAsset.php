<?php
namespace dvizh\promocode\assets;

use yii\web\AssetBundle;

class AddNewAsset extends AssetBundle
{
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    public $js = [
        'js/add_new.js',
        'js/promocode_cumulative.js',
    ];
    
    public $css = [
        
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/../web';
        parent::init();
    }
}
