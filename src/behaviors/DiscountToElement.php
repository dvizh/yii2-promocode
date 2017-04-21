<?php
namespace dvizh\promocode\behaviors;

use yii;
use yii\base\Behavior;

class DiscountToElement extends Behavior
{
    public $eventName = 'element_cost';

    public function events()
    {
        $eventName = $this->eventName;

        return [
            $eventName => 'doDiscount'
        ];
    }

    public function doDiscount($event)
    {
        if (yii::$app->promocode->has() && $targetModels = yii::$app->promocode->getTargetModels()) {
            
            if (!yii::$app->promocode->get()->promocode->getTransactions()->all() && yii::$app->promocode->get()->promocode->type == 'cumulative') {
                $discount = 0;
            } else {
                $discount = yii::$app->promocode->get()->promocode->discount;
            }
            
            if (yii::$app->promocode->get()->promocode->type == 'percent' || yii::$app->promocode->get()->promocode->type == 'cumulative'
                || empty(yii::$app->promocode->get()->promocode->type)) {
                if ($discount > 0 && $discount <= 100 && $event->cost > 0) {
                    foreach ($targetModels as $target) {
                        if ($target->item_model == $event->element->model && $target->item_id == $event->element->getModel()->id) {
                            $event->cost = $event->cost - (($event->cost * $discount) / 100);
                        }
                    }
                }
            } else {
                if ($discount > 0 && $event->cost > 0) {
                    foreach ($targetModels as $target) {
                        if ($target->item_model == $event->element->model && $target->item_id == $event->element->getModel()->id) {
                            if ($event->cost < $discount) {
                                $event->cost = $event->cost - (($event->cost * 100) / 100);
                            } else {
                                $event->cost = $event->cost - $discount;
                            }
                        }
                    }
                }
            }
        }

        return $this;
    }
}
