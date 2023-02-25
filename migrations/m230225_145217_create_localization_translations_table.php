<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%localization_translations}}`.
 */
class m230225_145217_create_localization_translations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%localization_translations}}', [
            'localization_key_id' => $this->integer(),
            'language_id' => $this->integer(),
            'translation' => $this->text()->notNull(),
            'PRIMARY KEY(localization_key_id, language_id)'
        ]);

        $this->addForeignKey(
            'fk-localization_translations-localization_key_id',
            'localization_translations',
            'localization_key_id',
            'localization_keys',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-localization_translations-language_id',
            'localization_translations',
            'language_id',
            'languages',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%localization_translations}}');
    }
}
