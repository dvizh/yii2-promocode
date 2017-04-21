<?php
namespace dvizh\promocode;

class Module extends \yii\base\Module
{
    public $adminRoles = ['admin', 'superadmin'];
    public $fields = [];
    public $targetModelList = null;
    public $clientsModel = null;
    public $orderModel = null;
    public $informer = 'dvizh\cart\widgets\CartInformer';
    public $informerSettings = [];
    
    public function init()
    {
        parent::init();
    }
}
