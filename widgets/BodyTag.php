<?php

namespace wma\widgets;

use Yii;
use yii\base\Widget;
use wmc\helpers\Html;

class BodyTag extends Widget
{
    private $_classes = [];
    private $_htmlOptions = [];
    private $_themeIndex = ['dark-elegance' => 'smart-style-1','ultra-white' => 'smart-style-2','google' => 'smart-style-3'];
    private $_navStyleIndex = ['minified' => 'minified','hidden' => 'hidden-menu','top' => 'menu-on-top'];

    /**
     * Array of options that will be sent along with auto generated class option.
     * Class option will be appended to auto generated classes.
     * @param $htmlOptions array ['key' => 'value'] options to be passed to body tag
     */

    public function setHtmlOptions($htmlOptions) {
        if (is_array($htmlOptions)) {
            $this->_htmlOptions = $htmlOptions;
        }
    }

    public function init()
    {
        parent::init();
        // Theme
        if (Yii::$app->adminSettings->getOption('template.theme') !== 'default') {
            $this->_classes[] = $this->_themeIndex[Yii::$app->adminSettings->getOption('template.theme')];
        }
        // Nav Style
        if (Yii::$app->adminSettings->getOption('template.navStyle') !== 'default') {
            $this->_classes[] = $this->_navStyleIndex[Yii::$app->adminSettings->getOption('template.navStyle')];
        }
        // Fixed Layout
        if (Yii::$app->adminSettings->getOption('template.fixedLayout') !== 'none') {
            if (Yii::$app->adminSettings->getOption('template.fixedLayout') == 'header') {
                $this->_classes = array_merge($this->_classes, ['fixed-header']);
            } else {
                if (Yii::$app->adminSettings->getOption('template.fixedLayout') == 'header+nav') {
                    $this->_classes = array_merge($this->_classes, ['fixed-header', 'fixed-navigation']);
                } else {
                    if (Yii::$app->adminSettings->getOption('template.fixedLayout') == 'header+nav+ribbon') {
                        $this->_classes = array_merge(
                            $this->_classes,
                            ['fixed-header', 'fixed-navigation', 'fixed-ribbon']
                        );
                    }
                }
            }
        }
        // Fixed Footer
        if (Yii::$app->adminSettings->getOption('template.fixedFooter') === true) {
            $this->_classes[] = 'fixed-footer';
        }
        // Fixed Width
        if (Yii::$app->adminSettings->getOption('template.fixedWidth') === true) {
            $this->_classes[] = 'fixed-width';
        }

        if (count($this->_classes) > 0) {
        $classes = implode(' ', $this->_classes);
            if (isset($this->_htmlOptions['class'])) {
                $this->_htmlOptions['class'] = $classes . ' ' . $this->_htmlOptions['class'];
            } else {
                $this->_htmlOptions['class'] = $classes;
            }
        }
    }

    public function run()
    {
        return Html::beginTag('body', $this->_htmlOptions);
    }
}