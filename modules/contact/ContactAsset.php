<?php

namespace wmf\modules\contact;

use yii\web\AssetBundle;

class ContactAsset extends AssetBundle
{
    public $publishOptions = [
        'forceCopy' => YII_DEBUG
    ];
    public $sourcePath = '@wmf/modules/contact/assets';
    public $css = [];
    public $js = [
        'js/contact.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}