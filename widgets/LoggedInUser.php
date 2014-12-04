<?php

namespace wma\widgets;

use Yii;
use wmc\helpers\Html;
use yii\base\Widget;
use rmrevin\yii\fontawesome\FA;

class LoggedInUser extends Widget
{
    private $_avatarSource;

    private $_displayName;

    public function setDisplayName($type) {
        $validNames = [
            'username' => Yii::$app->user->identity->username,
            'name' => Yii::$app->user->identity->person->first_name,
            'full_name' => Yii::$app->user->identity->person->first_name
                . "&nbsp;" . Yii::$app->user->identity->person->last_name,
            'email' => Yii::$app->user->identity->person->email
        ];
            $this->_displayName = $validNames[$type];
    }

    public function init() {
        if (!$this->_displayName) {
            $this->displayName = 'username';
        }
        // Future support for more than gravatar
        $gravatarHash = md5(strtolower(Yii::$app->user->identity->person->email));
        $this->_avatarSource = 'http://www.gravatar.com/avatar/' . $gravatarHash . '?s=120';
        parent::init();
    }

    public function run() {
        return Html::tag(
            'span',
            Html::a(
                Html::img(
                    $this->_avatarSource,
                    ['class' => 'online']
                )
                . Html::tag(
                    'span',
                    $this->_displayName
                )
                . '&nbsp;'
                . FA::icon('angle-down'),
                "javascript:void(0);",
                [
                    'id' => 'show-shortcut',
                    'data-action' => 'toggleShortcut'
                ]
            )
        );
    }
}