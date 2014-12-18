<?php

namespace wmf\modules\contact\widgets;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\web\JsExpression;
use wmc\helpers\Html;
use yii\helpers\Json;
use wmf\modules\contact\models\ContactForm;
use wmf\modules\contact\ContactAsset;

class Contact extends Widget
{
    const FORM_ID = 'contact-form';
    private $_model = null;

    public function init() {
        $this->_model = new ContactForm;
        $attributes = [
            'name' => Html::getInputId($this->_model, 'name'),
            'email' => Html::getInputId($this->_model, 'email'),
            'message' => Html::getInputId($this->_model, 'message'),
        ];
        // Register JS
        $js = new JsExpression("
            $('#".self::FORM_ID."').on('submit', function(e){
            e.preventDefault();
            WM.contact.postForm($(this), ".Json::encode($attributes).");
            });"
        );
        Yii::$app->view->registerJs($js);
        ContactAsset::register(Yii::$app->view);
    }

    public function run() {
        return $this->render('contact', ['model' => $this->_model]);
    }
}