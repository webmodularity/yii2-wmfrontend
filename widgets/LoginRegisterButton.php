<?php

namespace wma\widgets;

use Yii;
use wmc\helpers\Html;
use yii\base\Widget;

class LoginRegisterButton extends Widget
{
    const REGISTER_TEXT = 'New User?';
    const REGISTER_BUTTON = 'Register';
    const LOGIN_TEXT = 'Already Registered?';
    const LOGIN_BUTTON = 'Sign In';

    public function run() {
        if (Yii::$app->adminSettings->getOption('user.register.webRegistration') !== true) {
            return '';
        }
        $action = Yii::$app->controller->action->id;
        if ($action == 'login') {
            return Html::tag(
                'span',
                Html::tag('span', self::REGISTER_TEXT, ['class' => 'hidden-mobile']) .
                Html::a(self::REGISTER_BUTTON, 'register', ['class' => 'btn btn-danger']),
                ['id' => 'extr-page-header-space']
            );
        } else if (in_array($action, ['register', 'forgot-username', 'forgot-password'])) {
            return Html::tag(
                'span',
                Html::tag('span', self::LOGIN_TEXT, ['class' => 'hidden-mobile']) .
                Html::a(self::LOGIN_BUTTON, 'login', ['class' => 'btn btn-danger']),
                ['id' => 'extr-page-header-space']
            );
        } else {
            return '';
        }
    }
}