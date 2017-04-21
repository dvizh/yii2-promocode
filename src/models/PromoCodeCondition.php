<?php

namespace dvizh\promocode\models;


class PromoCodeCondition extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%promocode_condition}}';
    }

    public function rules()
    {
        return [
            [['sum_start', 'sum_stop', 'value'], 'required'],
            [['sum_start', 'sum_stop', 'value'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sum_start' => 'Начальная сумма',
            'sum_stop' => 'Конечная сумма',
            'value' => '% скидки',
        ];
    }
}