<?php

namespace wmf\modules\contact\controllers;

use Yii;
use wmf\web\Controller;
use yii\helpers\Json;
use wmc\helpers\Html;
use wmf\modules\contact\models\ContactForm;

class DefaultController extends Controller {
    public function actionIndex() {
        $model = new ContactForm();
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $model->attributes = Yii::$app->request->post('ContactForm');
            $responseArray = [
                'responseCode' => 200,
                'errors' => []
            ];
            if (!$model->validate()) {
                // Validation Error
                $responseArray['responseCode'] = 500;
                $responseArray['errors'] = $model->getErrors();
            } else {
                $this->send($model);
            }
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return Json::encode($responseArray);
        } else if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::$app->alertManager->add(
                'success',
                'Thanks, your message has been sent and we will respond to you ASAP.',
                'Message Received'
            );
            $this->send($model);
            $model = new ContactForm();
        }
        if (isset($this->module->viewLayoutFile)) {
            $this->layout = $this->module->viewLayoutFile;
        }
        return $this->render($this->module->viewFile, ['model' => $model]);
    }

    protected function send($model) {
        // Send Mail
        $emailSent = Yii::$app->mailer->compose($this->module->emailTemplateFile, ['model' => $model])
            ->setFrom($model->email)
            ->setTo(Yii::$app->params['adminEmail'])
            ->setSubject(Yii::$app->params['siteName'] . ' Web Contact from ' . Html::encode($model->name))
            ->send();
        if ($emailSent === true) {
            Yii::info("Sent: Contact Message from (".$model->email.") sent to ".Yii::$app->params['adminEmail']."."
                , 'contact');
        } else {
            Yii::error("Failed to send: Contact Message from (".$model->email.") sent to ".Yii::$app->params['adminEmail']."."
                , 'contact');
        }
    }

}