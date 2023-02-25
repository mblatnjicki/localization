<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%localization_keys}}`.
 */
class m230225_145206_create_localization_keys_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%localization_keys}}', [
            'id' => $this->primaryKey(),
            'key' => $this->string()->notNull()->unique()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%localization_keys}}');
    }
}
