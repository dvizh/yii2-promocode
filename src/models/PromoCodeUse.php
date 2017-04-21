<?php
namespace dvizh\promocode\models;

use Yii;

class PromoCodeUse extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%promocode_use}}';
    }

    public function rules()
    {
        return [
            [['user_id', 'promocode_id', 'date'], 'required'],
            [['promocode_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Идентификатор пользователя',
            'promocode_id' => 'Промокод',
            'user_id' => 'Пользователь',
            'date' => 'Дата использования',
        ];
    }

    public function getPromocode()
    {
        return $this->hasOne(PromoCode::className(), ['id' => 'promocode_id']);
    }
}
