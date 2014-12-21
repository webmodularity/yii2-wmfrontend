<?php
use yii\widgets\ActiveForm;
use wmc\helpers\Html;

$form = ActiveForm::begin([
    'id' => \wmf\modules\contact\widgets\Contact::FORM_ID,
    'action' => '/contact/post',
    'options' => [
        'class' => 'myform',
        'novalidate' => 'novalidate'
    ],
    'enableClientScript' => false
    ]);
?>
<div class="row clearfix">
    <div id="contact-alert" class="col-md-12"></div>
    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
            <label class="control-label">Name</label>
            <div class="controls">
                <?=  Html::activeInput('text', $model, 'name',
                    [
                        'class' => 'form-control input-lg requiredField',
                        'placeholder' => "Your Name"
                    ]); ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
            <label class="control-label">Email</label>
            <div class="controls">
                <?=  Html::activeInput('email', $model, 'email',
                    [
                        'class' => 'form-control input-lg requiredField',
                        'placeholder' => "Your Email"
                    ]); ?>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label">Message</label>
    <div class="controls">
        <?=  Html::activeTextarea($model, 'message',
            [
                'class' => 'form-control input-lg requiredField',
                'placeholder' => "Your Message",
                'rows' => 5
            ]); ?>
    </div>
</div>


<div class="form-group">
    <?=  $form->field($model, 'captcha')->widget('wmc\modules\recaptcha\widgets\Recaptcha'); ?>
</div>

<p>
    <?= Html::button("Send Message", ['class' => "btn btn-store btn-block", 'type' => "submit"]) ?>
</p>

<?php ActiveForm::end(); ?>

<br \>