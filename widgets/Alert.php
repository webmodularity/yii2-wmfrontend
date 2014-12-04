<?php

namespace wma\widgets;

use wmc\helpers\Html;

class Alert extends \wmc\widgets\Alert
{
    public $block = false;

    public function init() {
        parent::init();
        // Normalize block
        if (!is_bool($this->block)) {
            $this->block = false;
        }
    }

    public function getContainerOptions() {
        $options = parent::getContainerOptions();
        if ($this->block === true) {
            $options['class'] .= ' alert-block';
        }
        return $options;
    }

    public function getHeadingHtml()
    {
        if ($this->block === true) {
            return Html::tag('h4', $this->heading, ['class' => 'alert-heading']);
        } else {
            return parent::getHeadingHtml();
        }
    }

    public function getIconHtml() {
        if ($this->block === true) {
            return '';
        } else {
            return parent::getIconHtml();
        }
    }
}