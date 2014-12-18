<?php

namespace wmf\modules\contact\controllers;

use Yii;
use wmf\web\Controller;
use yii\web\HttpException;
use yii\helpers\Json;
use wmc\helpers\Html;
use wmf\modules\contact\models\ContactForm;

class PostController extends Controller
{

    public function actionIndex() {
        $model = new ContactForm;
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
        //if (1==1) {
            $model->attributes = Yii::$app->request->post('ContactForm');
            //$model->name = 'Rory';
            //$model->email = 'rory@wasabigaming.com';
            //$model->message = 'Test message!';
            $responseArray = [
                'responseCode' => 200,
                'errors' => []
            ];
            if (!$model->validate()) {
                // Validation Error
                $responseArray['responseCode'] = 500;
                $responseArray['errors'] = $model->getErrors();
            } else {
                // Send Mail
                Yii::$app->mailer->compose('@wmf/modules/contact/views/mail', ['model' => $model])
                    ->setFrom($model->email)
                    ->setTo(Yii::$app->params['adminEmail'])
                    ->setSubject(Yii::$app->params['siteName'] . ' Web Contact from ' . Html::encode($model->name))
                    ->send();
            }
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return Json::encode($responseArray);
        }
        throw new HttpException('404', "Server Error - Message Not Sent!");
    }

}