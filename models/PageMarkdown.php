<?php

namespace wmf\models;

use Yii;
use wmc\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%page_markdown}}".
 *
 * @property integer $page_id
 * @property integer $page_version
 * @property string $markdown
 * @property string $created_at
 *
 * @property Page $page
 */
class PageMarkdown extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page_markdown}}';
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['markdown'], 'default', 'value' => ''],
            [['markdown'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'page_id' => 'Page ID',
            'page_version' => 'Page Version',
            'markdown' => 'Page Content',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }
}