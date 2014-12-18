<?php
use wmc\helpers\Html;
?>
<p>Message sent from <?= Html::mailto(Html::encode($model->name), $model->email) ?> at <?= \Yii::$app->formatter->asDatetime('now', 'short') . '&nbsp;' . date('T') ?></p>

&gt;&gt;&gt;Message&gt;&gt;&gt;<br />
<?= Html::encode($model->message) ?><br />
&lt;&lt;&lt;Message&lt;&lt;&lt;