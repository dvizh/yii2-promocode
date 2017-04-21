<?php
namespace dvizh\promocode\controllers;

use yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\helpers\Html;
use yii\filters\VerbFilter;

class PromoCodeUseController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    public function actionEnter()
    {

        $informer = $this->module->informer;
        $informerSettings = $this->module->informerSettings;

        try {
            $promocode = yii::$app->request->post('promocode');
            
            if(yii::$app->request->post('clear')) {
                yii::$app->promocode->clear();
                $discount = false;
                $message = 'Промокод удален!';
            } else {
                yii::$app->promocode->enter($promocode);
                $transactions = yii::$app->promocode->get()->promocode->getTransactions()->all();
                if (yii::$app->promocode->get()->promocode->type === 'cumulative' && empty($transactions)) {
                    $discount = 0;
                } else {
                    $discount = yii::$app->promocode->get()->promocode->discount;
                }
                $message = 'Промокод применен, скидка ' . $discount;
                if (yii::$app->promocode->get()->promocode->type != 'quantum') {
                    $message .= '%';
                } else {
                    $message .= ' рублей';
                }
            }
            
            if(yii::$app->cart) {
                $newCost = yii::$app->cart->costFormatted;
            }
            else {
                $newCost = null;
            }

            return json_encode(['code' => Html::encode($promocode), 'informer' => $informer::widget($informerSettings), 'result' => 'success', 'newCost' => $newCost, 'message' => $message]);
        }
        catch(\Exception $e) {
            return json_encode(['informer' => $informer::widget($informerSettings), 'result' => 'fail', 'message' => $e->getMessage()]);
        }
    }
}
