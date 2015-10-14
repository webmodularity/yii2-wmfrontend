<?php

namespace wmf\web;

use Yii;
use wmf\models\Page;
use yii\web\NotFoundHttpException;

class PageAction extends \yii\base\Action
{
    public $viewFile = '@wmf/views/page/page';

    public function run($name) {
        $page = Page::findPageFromName($name);

        if (is_null($page)) {
            Yii::error("PageAction 404 on (".$name.")");
            throw new NotFoundHttpException("File not found.");
        }

        if (Yii::$app->user->isGuest) {
            $groupId = $userId = null;
        } else {
            $groupId = Yii::$app->user->identity->group_id;
            $userId = Yii::$app->user->id;
        }

        if ($page->groupHasAccess($groupId) === false) {
            Yii::error("PageAction 404[Bad Permissions] on (".$name.")");
            throw new NotFoundHttpException("File not found.");
        }

        // Breadcrumbs
        $breadcrumbs = $page->pageBreadcrumbs;
        if (count($breadcrumbs) == 0) {
            $this->controller->view->params['breadcrumbs'][] = $page->title;
        } else {
            foreach ($breadcrumbs as $breadcrumb) {
                if (is_null($breadcrumb->url)) {
                    $this->controller->view->params['breadcrumbs'][] = $breadcrumb->label;
                } else {
                    $this->controller->view->params['breadcrumbs'][] =
                        [
                            'label' => $breadcrumb->label,
                            'url' => $breadcrumb->url
                        ];
                }
            }
        }

        return $this->controller->render($this->viewFile,
            [
                'pageTitle' => $page->title,
                'html' => $page->html
            ]);
    }
}