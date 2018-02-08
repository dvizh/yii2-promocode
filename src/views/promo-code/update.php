<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PromoCodes */

$this->title = 'Изменить промокод: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Промокоды', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Изменить промокод '.$model->code;
?>
<div class="promo-codes-update">

    <?= $this->render('_form', [
        'model' => $model,
        'targetModelList' => $targetModelList,
        'items' => $items,
        'conditions' => $conditions,
        'clientsModelMap' => $clientsModelMap,
    ]) ?>

</div>
