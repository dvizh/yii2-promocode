<?php
namespace dvizh\promocode\controllers;

use yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class ToolsController  extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->module->adminRoles,
                    ]
                ]
            ],
        ];
    }

    public function actionProductWindow($targetModel = null)
    {
        $this->layout = '@vendor/dvizh/yii2-promocode/src/views/layouts/mini';

        $targetSearchModel = $this->module->targetModelList[$targetModel]['searchModel'];
        $targetSimpleModel = $this->module->targetModelList[$targetModel]['model'];
        $searchModel = new $targetSearchModel;
        $model = new $targetSimpleModel;

        $dataProvider = $searchModel->search(yii::$app->request->queryParams);

        return $this->render('product-window', [
            'searchModel' => $searchModel,
            'model' => $model,
            'dataProvider' => $dataProvider,
            'targetModel' => $targetSimpleModel,
        ]);
    }
}
