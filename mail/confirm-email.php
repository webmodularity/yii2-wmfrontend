<?php
use yii\helpers\Html;
use yii\helpers\Url;
$confirmLink = Url::toRoute(['/user/confirm', 'key' => $key], true);
?>
<p class="callout"><span class="lead">Please confirm your email address by clicking the link below:</span><br/><br />
    <?= Html::a('Click Here to confirm ' . $emailAddress,$confirmLink) ?></p>
<p style="font-style: italic;"> * If you did not request this account action or you do not wish to register this email address you can safely ignore this email and no further action is required.</p>
<p>If you are unable to click the link above you can confirm your email address by copying
<?= $confirmLink ?> into your browser.</p>