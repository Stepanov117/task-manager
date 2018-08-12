<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Status model
 *
 * @property integer $id
 * @property string $name
 * @property string $order
 */
class Status extends ActiveRecord
{
    /** {@inheritdoc} */
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],
            ['order', 'filter', 'filter' => 'intval'],
        ];
    }
    
    /** {@inheritdoc} */
    public static function tableName()
    {
        return '{{status}}';
    }
    
    /** {@inheritdoc} */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $statuses = Status::find()->where(['>', 'order', $this->order])->andWhere(['!=', 'id', $this->id])->all();
        foreach($statuses as $status){
            $status->order--;
            $status->save();
        }
        $tasks = Task::find()->where(['status_id' => $this->id])->all();
        foreach($tasks as $task){
            $task->status_id = null;
            $task->save();
        }
    }

    /**
    * Add new status.
    *
    * @return bool flag if new status had saved.
    */
    public function add()
    {
        if (!$this->validate()) {
            return false;
        }
        $status = new Status();
        $status->name = $this->name;
        $maxOrder = Status::find()->orderBy('order DESC')->one();
        $status->order = 1;
        if(!empty($maxOrder)){
            $status->order = $maxOrder->order + 1;
        }
        if(!$status->save()){
            return false;
        }
        return true;
    }

    /**
    * Edit status.
    *
    * @return bool if status had updated.
    */
    public function edit(Status $original)
    {
        if (!$this->validate()) {
            return false;
        }
        if($this->order < $original->order) {
            $this->order++;
            $params = ['between', 'order', $this->order, $original->order];
            $delta = 1;
        }
        if($this->order > $original->order) {
            $params = ['between', 'order', $original->order, $this->order];
            $delta = -1;
        }
        if($this->order !== $original->order){
            if(!$original->order > 0) {
                $params = ['>', 'order', $this->order];
            }
            $statuses = Status::find()->where($params)->andWhere(['!=', 'id', $this->id])->all();
            foreach($statuses as $status){
                $status->order += $delta;
                $status->save();
            }
        }
        if(!$this->save()){
            return false;
        }
        return true;
    }
}