<?php

namespace wmf\controllers;

use Yii;
use \yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'file' => [
                'class' => 'wmc\web\FileAction'
            ],
            'page' => [
                'class' => 'wmf\web\PageAction',
                'viewFile' => '@frontend/views/site/page'
            ]
        ];
    }

}