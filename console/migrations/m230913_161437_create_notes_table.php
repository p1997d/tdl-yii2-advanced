<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notes}}`.
 */
class m230913_161437_create_notes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notes}}', [
            'id' => $this->primaryKey()->notNull()->unsigned(),
            'title' => $this->text()->notNull(),
            'description' => $this->text()->notNull(),
            'status' => $this->tinyInteger()->notNull()->unsigned()->defaultValue(0),
            'position' => $this->integer()->notNull()->unsigned(),
            'date_created' => $this->integer()->notNull()->unsigned(),
            'date_updated' => $this->integer()->null()->unsigned(),
            'date_execute' => $this->integer()->null()->unsigned(),
            'date_started' => $this->integer()->null()->unsigned(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%notes}}');
    }
}
