<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

use app\models\Status;

/**
 * Task model
 *
 * @property integer $id
 * @property string $name
 * @property string $desc
 * @property int $created_at
 * @property int $updated_at
 * @property int $completed_at
 * @property int $status_id
 */
class Task extends ActiveRecord
{
    /** @inheritdoc */
    public static function tableName()
    {
        return '{{task}}';
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],
            ['desc', 'string', 'max' => 4000],
            ['status_id', 'filter', 'filter' => 'intval'],
        ];
    }

    /** @inheritdoc */
    public function behaviors()
    {
        return [TimestampBehavior::className()];
    }
 
    /** @inheritdoc */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /** @inheritdoc */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add new task.
     * 
     * @return bool is successfull create.
     */
    public function add()
    {
        if (!$this->validate()) {
            return false;
        }
        if(!$this->save()){
            return false;
        }
        return true;
    }

    /*public function update($runValidation = true, $attributeNames = null)
    {
        parent::update();
        if(!$this->save()){
            return false;
        }
        return true;
    }*/

    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }

    public function getCreated(string $format = 'Y-m-d H:i:s')
    {
        return date($format, $this->created_at);
    }

    public function getUpdated(string $format = 'Y-m-d H:i:s')
    {
        return date($format, $this->updated_at);
    }

    public function getCompleted(string $format = 'Y-m-d H:i:s')
    {
        if(empty($this->completed_at)){
            return null;
        }
        return date($format, $this->completed_at);
    }
}
