<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%notes}}`.
 */
class m230920_061822_add_user_id_column_to_notes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%notes}}', 'user_id', $this->integer()->notNull()->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%notes}}', 'user_id');
    }
}
