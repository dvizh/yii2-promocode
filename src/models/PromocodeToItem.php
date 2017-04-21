<?php

namespace dvizh\promocode\models;

use Yii;


class PromocodeToItem extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return 'promocode_to_item';
    }

    public function rules()
    {
        return [
            [['promocode_id', 'item_model', 'item_id'], 'required'],
            [['promocode_id', 'item_id'], 'integer'],
            [['item_model'], 'string', 'max' => 255],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'promocode_id' => 'ID Помокода',
            'item_model' => 'Модель',
            'item_id' => 'ID Экземпляра модели',
        ];
    }
} 