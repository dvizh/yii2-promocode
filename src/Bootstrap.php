<?php
namespace dvizh\promocode;

use yii\base\BootstrapInterface;
use yii;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if(!$app->has('promocode')) {
            $app->set('promocode', ['class' => 'dvizh\promocode\Promocode']);
        }
        
        return true;
    }
}