<?php

namespace wmf\models;

use Yii;
use wmu\models\UserGroup;
use wmc\behaviors\TimestampBehavior;

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
class Page extends \wmc\db\ActiveRecord
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
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'title', 'html'], 'required'],
            [['html'], 'string'],
            [['status'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
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
            'html' => 'Html',
            'layout' => 'Layout',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserGroups()
    {
        return $this->hasMany(UserGroup::className(), ['id' => 'user_group_id'])->viaTable('{{%page_access}}', ['page_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageBreadcrumbs()
    {
        return $this->hasMany(PageBreadcrumb::className(), ['page_id' => 'id'])->orderBy('order_by');
    }

    public static function findPageFromName($name) {
        if (empty($name) || !is_string($name)) {
            return null;
        }

        return static::findOne(['name' => $name, 'status' => 1]);
    }

    public function groupHasAccess($groupId) {
        if (count($this->userGroups) == 0) {
            return true;
        } else if (is_null($groupId)) {
            return false;
        } else {
            foreach ($this->userGroups as $userGroup) {
                if ($userGroup->id == $groupId) {
                    return true;
                }
            }
            return false;
        }
    }
}