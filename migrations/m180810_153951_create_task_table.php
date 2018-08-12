<?php

use yii\db\Migration;

/**
 * Handles the creation of table `task`.
 */
class m180810_153951_create_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('task', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'desc' => $this->string(4000),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),
            'completed_at' => $this->integer(),
            'status_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('task');
    }
}
