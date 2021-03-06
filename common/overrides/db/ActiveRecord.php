<?php
namespace common\overrides\db;

use common\overrides\db\ActiveRecordQuery;
use Yii;
use yii\behaviors\TimestampBehavior;

class ActiveRecord extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timeStampBehavior' => TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     * @return ActiveRecordQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ActiveRecordQuery(get_called_class());
    }

    /**
     * Overrode findAll to include condition also if needed
     * @inheritdoc
     */
    public static function findAll($condition = null)
    {
        if (!empty($condition)) {
            return parent::findAll($condition);
        }

        return self::find()->all();
    }

    /**
     * List of human-readable statuses
     * @return array
     */
    public static function getStatusTexts()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            self::STATUS_DELETED => Yii::t('app', 'Deleted'),
        ];
    }

    /**
     * Get current status human-readable value
     * @return string
     */
    public function getStatusText()
    {
        return (!empty($this->getStatusTexts()[$this->status])) ? $this->getStatusTexts()[$this->status] : '';
    }
}
