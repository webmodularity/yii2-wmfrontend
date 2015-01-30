<?php

namespace wmf\modules\contact;

class Module extends \yii\base\Module
{
    public $viewFile = 'login';
    public $viewLayoutFile = null;
    public $emailTemplateFile = '@wmf/modules/contact/views/mail';
}