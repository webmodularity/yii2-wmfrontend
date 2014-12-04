<?php

namespace wma\web;

class UrlManager extends \yii\web\UrlManager
{
    public $enablePrettyUrl = true;
    public $showScriptName = false;
    public $rules = [
        'user/confirm/<key:.{32}>' => 'user/confirm'
    ];
}