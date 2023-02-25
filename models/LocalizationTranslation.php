<?php

namespace app\models;

use yii\db\ActiveRecord;

class LocalizationTranslation extends ActiveRecord
{

    public static function tableName()
    {
        return '{{localization_translations}}';
    }

    public function rules()
    {
        return [
            [['localization_key_id', 'language_id'], 'required'],
            [['translation'], 'string']
        ];
    }


    public function saveOrUpdate()
    {
        echo $this->localization_key_id; die;
    }
}