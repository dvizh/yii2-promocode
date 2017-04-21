<?php

use yii\grid\GridView;

$this->registerJs("$('[data-role=add-model]').on('click', function() {
        var id = $(this).data('target-model-id');
        var name = $(this).data('target-model-name');
        var targetModel = $(document).find('[data-role=target-model]').val();
        existItem = window.parent.dvizh.promocode.getTargetItem(id,targetModel);
        if (existItem) {
            alert('Элемент уже добавлен!');
        } else {
            window.parent.dvizh.promocode.updateModelList(id,targetModel,name);
        }
    });");
?>
<div class="product-window">
    <input type="hidden" value="<?=$targetModel?>" data-role="target-model">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'content' => function($model) {
                    return $model->name;
                }
            ],
            [
                'format' => 'raw',
                'content' => function ($model) {
                    return '<div class="btn btn-default" 
                        data-role="add-model"
                        data-target-model-name="'.$model->name.'"
                        data-target-model-id="'.$model->id.'">
                        Добавить</div>';
                }
            ]
        ],
    ]); ?>
</div>