<?php
namespace dvizh\promocode;

use yii\base\Component;
use dvizh\promocode\models\PromoCode as PromoCodeModel;
use dvizh\promocode\models\PromoCodeUse;
use dvizh\promocode\models\PromoCodeUsed;
use dvizh\promocode\events\PromocodeEvent;
use dvizh\promocode\models\PromoCodeCondition;
use dvizh\promocode\models\PromoCodeToCondition;
use yii;

class Promocode extends Component
{
    const EVENT_PROMOCODE_ENTER = 'promocode_enter';

    public $promocode = NULL;
    public $promocodeUse = NULL;

    private $userId;

    public function init()
    {
        $this->promocode = new PromoCodeModel;
        $this->promocodeUse = new PromoCodeUse;

        $session = yii::$app->session;

        if (!$userId = yii::$app->user->id) {
            if (!$userId = $session->get('tmp_user_id')) {
                $userId = md5(time() . '-' . yii::$app->request->userIP . Yii::$app->request->absoluteUrl);
                $session->set('tmp_user_id', $userId);
            }
        }

        $this->userId = $userId;

        parent::init();
    }

    public function enter($promocodeId)
    {

        $promocode = $this->promocode;


        if (!$promocodeModel = $promocode::findOne(['code' => $promocodeId])) {
            throw new \Exception('Промокод не найден');
        }

        if ($promocode::findOne(['code' => $promocodeId])->status == 0) {
            throw new \Exception('Промокод не действителен');
        }

        if (!$this->checkPromoCodeStatus($promocodeId)) {
            throw new \Exception('Промокод не действителен');
        }

        $data = [];
        $data['PromoCodeUse']['promocode_id'] = $promocodeModel->id;

        $date = date('Y-m-d H:i:s');

        $data['PromoCodeUse']['date'] = $date;
        $data['PromoCodeUse']['user_id'] = $this->userId;

        if ($this->promocodeUse->load($data) && $this->promocodeUse->validate()) {
            $promocodeEvent = new PromocodeEvent(['code' => $promocodeId, 'data' => $data['PromoCodeUse']]);
            $this->trigger(self::EVENT_PROMOCODE_ENTER, $promocodeEvent);

            $this->clear();
            $this->promocodeUse->save();
            return true;
        } else {
            return false;
        }
    }

    public function getCode()
    {
        if (!$this->has()) {
            return false;
        }

        return $this->get()->promocode->code;
    }


    public function find()
    {
        $promocodeUse = $this->promocodeUse;
        return $promocodeUse::find()->where(['user_id' => $this->userId]);
    }

    public function get()
    {
        return $this->find()->one();
    }

    public function has()
    {
        if ($this->get()) {
            return true;
        } else {
            return false;
        }
    }

    public function clear()
    {
        if ($code = $this->get()) {
            return $code->delete();
        } else {
            return false;
        }
    }

    public function getTargetModels()
    {
        if ($code = $this->get()) {
            return $code->promocode->targetModels;
        } else {
            return false;
        }
    }

    public function getPromoCodeByCode($promoCode)
    {
        return PromoCodeModel::findOne(['code' => $promoCode]);
    }

    public function getPromoCodeByOrderId($orderId)
    {
        $promoCodeUse = PromoCodeUsed::find()->where(['order_id' => $orderId])->one();

        if (!empty($promoCodeUse)) {
            return PromoCodeModel::findOne($promoCodeUse->promocode_id);
        } else {
            return false;
        }
    }

    public function checkExists($code)
    {
        if ($promocodeModel = $this->getPromoCodeByCode($code)) {
            return $promocodeModel;
        } else {
            return false;
        }
    }

    public function setPromoCodeStatus($promoCode, $status)
    {
        $promoCode->status = $status;

        return $promoCode->save(false);
    }

    public function checkPromoCodeStatus($code)
    {

        $promoCode = $this->checkExists($code);

        if (empty($promoCode->date_elapsed)) {
            return true;
        }

        if (strtotime($promoCode->date_elapsed) < strtotime(date('Y:m:d H:m:s'))) {
            $this->setPromoCodeStatus($promoCode, 0);
            return false;
        } else {
            return true;
        }
    }

    public function checkPromoCodeDiscount($promoCodeId)
    {
        $promoCode = $this->promocode;

        return $promoCode::findOne($promoCodeId)->discount;
    }

    public function setPromoCodeAmount($promoCode)
    {
        if ($promoCode->amount == null) {
            return true;
        }

        if ($promoCode->amount > 0) {
            $promoCode->amount = $promoCode->amount - 1;
        }
        if ($promoCode->amount <= 0) {
            $this->setPromoCodeStatus($promoCode, 0);
        }
        return $promoCode->save();
    }

    public function setPromoCodeUse($promoCode, $orderId, $sum)
    {
        $model = new PromoCodeUsed();
        $model->promocode_id = $promoCode->id;
        $model->date = date('Y-m-d H:i:s');
        if (!empty(yii::$app->user->id)) {
            $model->user = yii::$app->user->id;
        }
        $model->order_id = $orderId;
        $model->sum = $sum;
        if ($model->validate()) {
            $this->setPromoCodeAmount($promoCode);
            $model->save();
            return true;
        } else {
            return $model->getErrors();
        }
    }

    public function getPromoCodeUsedSum($promoCodeId)
    {
        return $promoCodeUse = PromoCodeUsed::find()
            ->where(['promocode_id' => $promoCodeId])
            ->sum('sum');
    }

    public function clearPromoCodeUseHistory($orderId)
    {
        PromoCodeUsed::deleteAll(['order_id' => $orderId]);
    }

    public function checkPromoCodeCumulativeStatus($promoCodeId)
    {
        $discountValue = 0;
        $sum = $this->getPromoCodeUsedSum($promoCodeId);

        $promoCodeConditions = PromoCodeToCondition::find()
            ->where([
                'promocode_id' => $promoCodeId,
            ])->all();

        $condition = PromoCodeCondition::find()
            ->where(['id' => yii\helpers\ArrayHelper::getColumn($promoCodeConditions, 'condition_id')])
            ->andWhere(['<', 'sum_start', (int)$sum])
            ->andWhere(['>', 'sum_stop', (int)$sum])
            ->one();
        if ($condition) {
            $discountValue = $condition['value'];
        } else {
            $condition = PromoCodeCondition::find()
                ->where(['id' => yii\helpers\ArrayHelper::getColumn($promoCodeConditions, 'condition_id')])
                ->orderBy(['sum_start' => SORT_ASC])
                ->limit(1)
                ->one();

            if ($condition  && $condition['sum_start'] > $sum ) {
                $discountValue = 0;
            } else {
                $condition = PromoCodeCondition::find()
                    ->where(['id' => yii\helpers\ArrayHelper::getColumn($promoCodeConditions, 'condition_id')])
                    ->orderBy(['sum_stop' => SORT_DESC])
                    ->limit(1)
                    ->one();

                if ($condition  && $condition['sum_stop'] < $sum ) {
                    $discountValue = $condition['value'];
                }
            }
        }
        if ($discountValue != $this->checkPromoCodeDiscount($promoCodeId)) {
            $this->setPromoCodeDiscount($promoCodeId, $discountValue);
        }

    }

    public function setPromoCodeToCondition($promoCodeId, $conditionId)
    {
        $model = PromoCodeToCondition::find()
            ->where([
                'promocode_id' => $promoCodeId,
                'condition_id' => $conditionId,
            ])->one();
        if (!$model) {
            $model = new PromoCodeToCondition();
            $model->promocode_id = $promoCodeId;
            $model->condition_id = $conditionId;
            return $model->save();
        }

        return true;
    }

    public function setPromoCodeDiscount($promoCodeId, $percent)
    {
        $promoCode = $this->promocode;

        $promoCode = $promoCode::findOne($promoCodeId);

        if ($percent != $this->checkPromoCodeDiscount($promoCodeId)) {
            $promoCode->discount = $percent;
            return $promoCode->save(false);
        }

        return true;
    }

    public function addConditions($conditions, $promoCodeId)
    {
        $isfirst = 0;

        foreach ($conditions as $key => $condition) {

            if (!$isfirst++) {
                if ($condition['sumStart'] == 0) {
                    if ($condition['percent'] != $this->checkPromoCodeDiscount($promoCodeId))
                        $this->setPromoCodeDiscount($promoCodeId, $condition['percent']);
                } else {
                    $this->setPromoCodeDiscount($promoCodeId, 0);
                }
            }

            $model = PromoCodeCondition::find()
                ->where([
                    'id' => $key,
                ])
                ->one();

            if (!$model) {
                $model = new PromoCodeCondition();
                $model->sum_start = $condition['sumStart'];
                $model->sum_stop = $condition['sumStop'];
                $model->value = $condition['percent'];
                $model->save();
            } else {
                $model->sum_start = $condition['sumStart'];
                $model->sum_stop = $condition['sumStop'];
                $model->value = $condition['percent'];
                $model->save(false);
            }

            $this->setPromoCodeToCondition($promoCodeId, $model->id);
        }
    }

    public function rollbackPromoCodeUse($orderId)
    {

        $promoCode = $this->getPromoCodeByOrderId($orderId);

        if ($promoCode) {
            $this->clearPromoCodeUseHistory($orderId);
            if ($promoCode->type != 'cumulative') {
                return true;
            } else {
                $this->checkPromoCodeCumulativeStatus($promoCode->id);

            }
        }
    }
}