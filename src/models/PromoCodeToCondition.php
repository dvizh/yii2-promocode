<?php

namespace dvizh\promocode\models;

use Yii;

class PromoCodeToCondition extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%promocode_to_condition}}';
    }

    public function rules()
    {
        return [
            [['promocode_id', 'condition_id'], 'required'],
            [['promocode_id', 'condition_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'promocode_id' => 'ID Помокода',
            'condition_id' => 'ID Экземпляра условия',
        ];
    }

    public function getCondition()
    {
        return $this->hasOne(PromoCodeCondition::className(),['id' => 'condition_id']);
    }
}