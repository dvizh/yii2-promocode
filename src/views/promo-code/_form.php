<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use kartik\date\DatePicker;
use yii\helpers\Url;
use dvizh\promocode\assets\Asset;
Asset::register($this);

?>

<div class="promo-codes-form">
    <div class="container">
            <?php $form = ActiveForm::begin(); ?>
            <input type="hidden" name="backUrl" value="<?=Html::encode(yii::$app->request->referrer);?>" />
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'description')->textarea(); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        function KeyPromoGen(){
                            $key = md5(time());
                            $new_key = '';

                            for($i=1; $i <= 4; $i ++ ){
                                $new_key .= $key[$i];
                            }
                            return strtoupper($new_key);
                        }
                        if($model->isNewRecord) {
                            $code = KeyPromoGen();
                            $date = '';
                            $params = ['value' => $code];
                        } else {
                            $params = [];
                            if ($model->date_elapsed) {
                                $date = date('d.m.Y',strtotime($model->date_elapsed));
                            } else {
                                $date = '';
                            }
                        }
                        echo $form->field($model, 'code')->textInput($params) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'status')->dropDownList([
                            '1' => 'Активен',
                            '0' => 'Отключен',
                        ]);
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'type')->dropDownList([
                            'percent' => 'Процент скидки',
                            'quantum' => 'Сумма скидки',
                            'cumulative' => 'Накопительная скидка'
                        ],
                            [
                                'prompt' => 'Выберите тип скидки промокода:',
                                'class' => 'form-control promo-code-discount-type',
                            ])->hint('Выберите тип предоставляемой промокодом скидки')->label('Тип скидки промокода')
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'discount')->textInput()->hint('Задайте процент или сумму') ?>
                    </div>
                </div>
                <div class="col-md-12 promocode-cumulative-form form-group <?= (empty($conditions) || ($model->type != 'cumulative')) ? 'hidden' : '' ?>">
                    <div class="row text-center">
                        <?php if ($model->getTransactions()->all()) { ?>
                            <div class="col-md-6 alert alert-info">
                                <i>Сумма покупок: <b><?php if (!$model->isNewRecord) { echo (yii::$app->promocode->getPromoCodeUsedSum($model->id)) ? yii::$app->promocode->getPromoCodeUsedSum($model->id) : '0'; }?></b> р.</i>
                                <br>
                                <i>Скидка составляет <b><?php if (!$model->isNewRecord) { echo (yii::$app->promocode->checkPromoCodeDiscount($model->id)); }?>%</b></i>
                            </div>
                        <?php } ?>
                        <div class="form form-inline cumulative-block">
                            <?php if (isset($conditions)) { ?>
                                <?php foreach ($conditions as $condition) { ?>
                                    <div class="cumulative-row form-group">
                                        <input class="form-control" name="Conditions[<?= $condition['id']?>][sumStart]" type="text" value="<?= $condition['sum_start'] ?>" placeholder="От"> -
                                        <input class="form-control" name="Conditions[<?= $condition['id']?>][sumStop]" type="text" value="<?= $condition['sum_stop'] ?>" placeholder="До">
                                        <input class="form-control" name="Conditions[<?= $condition['id']?>][percent]" type="text" style="width: 50px" value="<?= $condition['value'] ?>" placeholder="%">
                                        <span class="btn glyphicon glyphicon-remove remove-condition-btn" style="color: red;"
                                              data-role="remove-row"
                                              data-href="ajax-delete-condition"
                                              data-condition="<?= $condition['id']?>">
                                        </span>
                                    </div>
                                <?php }
                            } else { ?>
                                <div class="cumulative-row form-group">
                                    <input class="form-control" name="Conditions[C0][sumStart]" type="text"placeholder="От"> -
                                    <input class="form-control" name="Conditions[C0][sumStop]" type="text"placeholder="До">
                                    <input class="form-control" name="Conditions[C0][percent]" type="text" style="width: 50px"placeholder="%">
                                        <span class="btn glyphicon glyphicon-remove remove-condition-btn" style="color: red;"
                                              data-role="remove-row">
                                        </span>
                                </div>
                            <?php } ?>
                        </div>
                        <br>
                        <div class="cl-md-2 col-md-offset-10">
                            <button class="btn btn-primary add-cumulative-row">
                                <span class="qlyphicon glyphicon-plus"></span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'date_elapsed')->widget(DatePicker::classname(), [
                            'language' => 'ru',
                            'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                            'options' => [
                                'placeholder' => 'Дата истечения промокода',
                                'value' => $date,
                            ],
                            'removeButton' => false,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'd.m.yyyy',
                            ],
                        ])->label('Дата истечения промокода')->hint('Выберите дату истечения срока действия промокода')
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'amount')->textInput()->label('Количество использований')->hint('Здесь задается количество использований промокода')
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'data-role' => 'sendForm']) ?>
                </div>
            </div>
                <div class="col-md-6 promocode-right-column">
                    <?php if($targetModelList) { ?>
                    <h3>Прикрепить только к:</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <?php foreach($targetModelList as $modelName => $modelType){   ?>
                                <?php
                                Modal::begin([
                                    'header' => '<h2>Привязать промокод к: '.$modelName.'</h2>',
                                    'size' => 'modal-lg',
                                    'toggleButton' => [
                                        'tag' => 'button',
                                        'class' => 'btn btn-sm btn-block btn-primary',
                                        'label' => $modelName . ' <i class="glyphicon glyphicon-plus"></i>',
                                        'data-model' => $modelType['model'],
                                    ]
                                ]);
                                ?>
                                <iframe src="<?=Url::toRoute(['/promocode/tools/product-window', 'targetModel' => $modelName]); ?>" frameborder="0" style="width: 100%; height: 400px;">
                                </iframe>
                                <?php
                                Modal::end();
                                ?>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table table-bordered">
                            <tbody data-role="model-list" id="modelList">
                            <?php
                            if (isset($items)) {
                                foreach ($items as $item) {
                                    foreach ($item as $item_id => $item_attr) {
                                        ?>
                                        <tr data-role="item">
                                            <td><label>
                                                    <?=$item_attr['name']?>   
                                                </label>
                                                <input type="hidden" data-role="product-model" name="targetModels<?=$item_id?>"
                                                       data-name="<?= str_replace(['[',']','\\'],"",$item_id)?>"/>
                                            </td>
                                            <td>
                                                <span data-href="ajax-delete-target-item" class="btn glyphicon glyphicon-remove" style="color: red;"
                                                      data-role="remove-target-item"
                                                      data-target-model="<?=$item_attr['model'] ?>"
                                                      data-target-model-id="<?=$item_attr['model_id'] ?>"></span>
                                            </td>

                                        </tr>
                                    <?php    }
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>
                    <div>
                        <?php if ($model->getTransactions()->all()) { ?>
                            <h3>История использований</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Дата использования</th>
                                            <th>Номер заказа</th>
                                            <th>Сумма</th>
                                            <th>Кем использован</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach($model->getTransactions()->orderBy(['date' => SORT_DESC])->all() as $promoCodeUse) {?>
                                            <tr>
                                                <td><?= date('d.m.Y H:i:s',strtotime($promoCodeUse->date)) ?></td>
                                                <td><a href="<?=Url::to(['/order/order/view', 'id' => $promoCodeUse->order_id]) ?>"><?= $promoCodeUse->order_id ?></a></td>
                                                <td><i><b><?= $promoCodeUse->sum ?> р.</b></i></td>
                                                <td><?= ($promoCodeUse->user) ? $clientsModelMap[$promoCodeUse->user] : '' ?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                        <?php } ?>
                    </div>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
