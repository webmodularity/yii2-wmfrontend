<?php

namespace wmf\models;

use Yii;

/**
 * This is the model class for table "{{%page_breadcrumb}}".
 *
 * @property integer $id
 * @property integer $page_id
 * @property string $label
 * @property string $url
 * @property integer $order_by
 *
 * @property Page $page
 */
class PageBreadcrumb extends \wmc\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page_breadcrumb}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id', 'label'], 'required'],
            [['page_id', 'order_by'], 'integer'],
            [['label', 'url'], 'string', 'max' => 255],
            [['page_id', 'order_by'], 'unique', 'targetAttribute' => ['page_id', 'order_by'], 'message' => 'The combination of Page ID and Order By has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page_id' => 'Page ID',
            'label' => 'Label',
            'url' => 'Url',
            'order_by' => 'Order By',
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