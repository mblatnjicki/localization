<?php

namespace app\models;

use yii\db\ActiveRecord;

class LocalizationKey extends ActiveRecord
{

    public static function tableName()
    {
        return '{{localization_keys}}';
    }

    public function rules()
    {
        return [
            [['key'], 'required']
        ];
    }
}