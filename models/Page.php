<?php

namespace wmf\models;

use Yii;
use wmc\models\user\UserGroup;
use wmc\behaviors\TimestampBehavior;
use wmc\behaviors\UserGroupAccessBehavior;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $html
 * @property string $layout
 * @property integer $status
 * @property string $updated_at
 * @property string $created_at
 *
 * @property UserGroup[] $userGroups
 * @property PageBreadcrumb[] $pageBreadcrumbs
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className()
            ],
            [
                'class' => UserGroupAccessBehavior::className(),
                'viaTableName' => '{{%page_access}}',
                'itemIdField' => 'page_id'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'title'], 'required'],
            [['layout'], 'default', 'value' => null],
            [['status'], 'integer'],
            [['name', 'title', 'layout'], 'string', 'max' => 255],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'title' => 'Title',
            'markdown' => 'Markdown',
            'html' => 'HTML',
            'layout' => 'Layout',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageBreadcrumbs()
    {
        return $this->hasMany(PageBreadcrumb::className(), ['page_id' => 'id'])->orderBy('order_by');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageMarkdown()
    {
        return $this->hasMany(PageMarkdown::className(), ['page_id' => 'id'])->orderBy(['page_version' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatestMarkdown()
    {
        return $this->hasOne(PageMarkdown::className(), ['page_id' => 'id'])->orderBy(['page_version' => SORT_DESC])->limit(1);
    }

    public static function findPageFromName($name) {
        if (empty($name) || !is_string($name)) {
            return null;
        }

        return static::findOne(['name' => $name, 'status' => 1]);
    }
}