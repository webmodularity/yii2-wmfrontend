<?php

namespace wma\widgets;

use wmc\helpers\Html;
use yii\helpers\ArrayHelper;
use rmrevin\yii\fontawesome\FA;

class ActiveField extends \yii\widgets\ActiveField
{
    const INVALID_CLASS = 'invalid';
    const ERROR_STATE_CLASS = 'state-error';
    const TOOLTIP_POSITIONS = 'left,right,top-left,top-right,bottom-left,bottom-right';

    public $inline = false;
    public $placeholder = null;
    public $template = "{label}\n{beginInputLabel}{iconPrepend}\n{iconAppend}
        {input}\n{tooltip}\n{endInputLabel}\n{error}\n{hint}";
    private $_inputLabelType = 'input';

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        $layoutConfig = [
            'hintOptions' => [
                'tag' => 'div',
                'class' => 'note'
            ],
            'errorOptions' => [
                'tag' => 'em',
                'class' => 'invalid'
            ],
            'labelOptions' => [
                'class' => 'label'
            ],
            'options' => [
                'tag' => 'section'
            ]
        ];
        $config = ArrayHelper::merge($layoutConfig, $config);
        return parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function render($content = null)
    {
        if ($content === null) {
            if (!isset($this->parts['{input}'])) {
                $this->parts['{input}'] = Html::activeTextInput($this->model, $this->attribute, $this->inputOptions);
            }
            if (!isset($this->parts['{label}'])) {
                $this->parts['{label}'] = Html::activeLabel($this->model, $this->attribute, $this->labelOptions);
            }
            if (!isset($this->parts['{beginInputLabel}'])) {
                $this->parts['{beginInputLabel}'] = Html::beginTag('label', ['class' => $this->_inputLabelType]);
                $this->parts['{endInputLabel}'] = Html::endTag('label');
            }
            if (!isset($this->parts['{error}'])) {
                $this->parts['{error}'] = Html::tag('span', '', ['class' => self::ERROR_STATE_CLASS])
                    . Html::error($this->model, $this->attribute, $this->errorOptions);
            }
            if (!isset($this->parts['{hint}'])) {
                $this->parts['{hint}'] = '';
            }
            if (!isset($this->parts['{tooltip}'])) {
                $this->parts['{tooltip}'] = '';
            }
            if (!isset($this->parts['{iconAppend}'])) {
                $this->parts['{iconAppend}'] = '';
            }
            if (!isset($this->parts['{iconPrepend}'])) {
                $this->parts['{iconPrepend}'] = '';
            }
            $content = strtr($this->template, $this->parts);
        } elseif (!is_string($content)) {
            $content = call_user_func($content, $this);
        }

        return $this->begin() . "\n" . $content . "\n" . $this->end();
    }

    public function iconAppend($faIconName = null)
    {
        if (is_string($faIconName)) {
            $this->parts['{iconAppend}'] = FA::icon($faIconName, ['class' => 'icon-append']);
        }
        return $this;
    }

    public function iconPrepend($faIconName = null)
    {
        if (is_string($faIconName)) {
            $this->parts['{iconPrepend}'] = FA::icon($faIconName, ['class' => 'icon-prepend']);
        }
        return $this;
    }

    public function tooltip($tooltipText, $position = 'top-right', $tooltipIcon = null, $tooltipIconOptions = [])
    {
        $position = in_array($position, explode(',', self::TOOLTIP_POSITIONS)) ? $position : 'top-right';
            if (!is_null($tooltipIcon)) {
                if (!is_array($tooltipIconOptions) || count($tooltipIconOptions) < 1) {
                    // Set to default tooltip color
                    $tooltipIconOptions = ['class' => $this->form->tooltipIconColorClass];
                }
                $tooltip = FA::icon($tooltipIcon, $tooltipIconOptions) . ' ' . $tooltipText;
            } else {
                $tooltip = $tooltipText;
            }
        $this->parts['{tooltip}'] = Html::tag('b', $tooltip, ['class' => 'tooltip tooltip-' . $position]);
        return $this;
    }

    public function colSpan($colspanLength) {
        if (is_int($colspanLength)) {
            if (isset($this->options['class'])) {
                $this->options['class'] .= ' col col-' . $colspanLength;
            } else {
                $this->options['class'] = ' col col-' . $colspanLength;
            }
        }
        return $this;
    }

    public function placeholder($placeholder = null)
    {
        if (is_null($placeholder)) {
            $this->inputOptions = ArrayHelper::merge(
                $this->inputOptions,
                ['placeholder' => $this->model->getAttributeLabel($this->attribute)]
            );
        } else if (is_string($placeholder)) {
            $this->inputOptions = ArrayHelper::merge(
                $this->inputOptions,
                ['placeholder' => $placeholder]
            );
        }
        $this->parts['{label}'] = '';
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function checkbox($options = [], $enclosedByLabel = true)
    {
        $class = isset($options['class']) ? $options['class'] . ' checkbox' : 'checkbox';
        $this->parts['{input}'] = Html::activeCheckbox($this->model, $this->attribute,
            [
                'label' => '<i></i>' . $this->model->getAttributeLabel(Html::getAttributeName($this->attribute)),
                'labelOptions' => ['class' => $class]
            ]
        );
        $this->parts['{beginInputLabel}'] = '';
        $this->parts['{endInputLabel}'] = '';
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function radio($options = [], $enclosedByLabel = true)
    {
        if ($enclosedByLabel) {
            if (!isset($options['template'])) {
                $this->template = $this->form->layout === 'horizontal' ?
                    $this->horizontalRadioTemplate : $this->radioTemplate;
            } else {
                $this->template = $options['template'];
                unset($options['template']);
            }
            if ($this->form->layout === 'horizontal') {
                Html::addCssClass($this->wrapperOptions, $this->horizontalCssClasses['offset']);
            }
            $this->labelOptions['class'] = null;
        }

        return parent::radio($options, false);
    }

    /**
     * @inheritdoc
     */
    public function checkboxList($items, $options = [])
    {
        if ($this->inline) {
            if (!isset($options['template'])) {
                $this->template = $this->inlineCheckboxListTemplate;
            } else {
                $this->template = $options['template'];
                unset($options['template']);
            }
            if (!isset($options['itemOptions'])) {
                $options['itemOptions'] = [
                    'labelOptions' => ['class' => 'checkbox-inline'],
                ];
            }
        }  elseif (!isset($options['item'])) {
            $options['item'] = function ($index, $label, $name, $checked, $value) {
                return '<div class="checkbox">'
                    . Html::checkbox($name, $checked, ['label' => $label, 'value' => $value])
                    . '</div>';
            };
        }
        parent::checkboxList($items, $options);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function radioList($items, $options = [])
    {
        if ($this->inline) {
            if (!isset($options['template'])) {
                $this->template = $this->inlineRadioListTemplate;
            } else {
                $this->template = $options['template'];
                unset($options['template']);
            }
            if (!isset($options['itemOptions'])) {
                $options['itemOptions'] = [
                    'labelOptions' => ['class' => 'radio-inline'],
                ];
            }
        }  elseif (!isset($options['item'])) {
            $options['item'] = function ($index, $label, $name, $checked, $value) {
                return '<div class="radio">'
                    . Html::radio($name, $checked, ['label' => $label, 'value' => $value])
                    . '</div>';
            };
        }
        parent::radioList($items, $options);
        return $this;
    }

    /**
     * @param bool $value whether to render a inline list
     * @return static the field object itself
     * Make sure you call this method before [[checkboxList()]] or [[radioList()]] to have any effect.
     */
    public function inline($value = true)
    {
        $this->inline = (bool)$value;
        return $this;
    }
}