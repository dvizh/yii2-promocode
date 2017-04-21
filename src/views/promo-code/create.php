<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\PromoCodes */

$this->title = 'Добавить промокод';
$this->params['breadcrumbs'][] = ['label' => 'Промокоды', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promo-codes-create">

    <?= $this->render('_form', [
        'model' => $model,
        'targetModelList' => $targetModelList,
    ]) ?>

</div>
