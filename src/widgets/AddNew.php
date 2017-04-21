<?php
namespace dvizh\promocode\widgets;

use yii\helpers\Html;
use yii\helpers\Url;
use dvizh\promocode\models\PromoCode;
use yii;

class AddNew extends \yii\base\Widget
{

    public $name = '';
    
    public function init()
    {
        parent::init();

        \dvizh\promocode\assets\AddNewAsset::register($this->getView());
    }

    public function run()
    {
        $model = new Promocode;
        
        $model->title = $this->name;
        
        $view = $this->getView();
        $view->on($view::EVENT_END_BODY, function($event) use ($model) {
            echo $this->render('add_new_form', ['model' => $model]);
        });
        
        return Html::a('<span class="glyphicon glyphicon-plus"></span>', '#promoCodesCreate', ['title' => 'Добавить промокод', 'data-toggle' => 'modal', 'data-target' => '#promoCodesCreate', 'class' => 'btn btn-success']);
    }
}