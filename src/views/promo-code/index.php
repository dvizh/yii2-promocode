<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PromoCodesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Промокоды';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promo-codes-index">

    <p>
        <?= Html::a('Добавить промокод', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php if (isset(yii::$app->getModule('promocode')->orderModel)) { ?>
        <div class="well pull-right">
            <p>
                <?= Html::a('Статистика по промокодам', ['statistic'], ['class' => 'btn btn-primary btn-block']) ?>
            </p>
            <p>
                <?= Html::a('Статистика за период', ['period-statistic'], ['class' => 'btn btn-primary btn-block']) ?>
            </p>
        </div>
    <?php } ?>  
    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'title',
                    'description:ntext',
                    'code',
                    'discount',
                    [
                        'attribute' => 'status',
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'status',
                            ['0' => 'Неактивно', '1' => 'Активно'],
                            ['class' => 'form-control', 'prompt' => 'Активность']
                        ),
                        'value' => function($model) {
                            if($model->status == 0) {
                                return 'Неактивно';
                            } else {
                                return 'Активно';
                            }
                        }
                    ],

                    ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}']
                ],
            ]); ?>
        </div>
    </div>


</div>
