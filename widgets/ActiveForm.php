<?php

namespace wma\widgets;

use wmc\helpers\ArrayHelper;

class ActiveForm extends \yii\widgets\ActiveForm
{
    public $tooltipIconColorClass = 'txt-color-teal';

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        $mergeConfig = [
            'options' => [
                'role' => 'form',
                'class' => 'smart-form',
            ],
            'requiredCssClass' => '',
            'errorCssClass' => 'state-error',
            'successCssClass' => 'state-success',
            'fieldClass' => 'wma\widgets\ActiveField',
            'validateOnBlur' => false,
            'validateOnChange' => false
        ];

        $config = ArrayHelper::mergeClass($mergeConfig, $config, ['options']);
        return parent::__construct($config);
    }
}