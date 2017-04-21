<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\date\DatePicker;
?>

<div class="promo-codes-form">

    <?php $form = ActiveForm::begin(['action' => ['/promocode/promo-code/create-widget']]); ?>
    <div class="row">
        <div class="col-md-12">
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
                            'autoclose'=>true,
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

    <?php ActiveForm::end(); ?>

</div>
