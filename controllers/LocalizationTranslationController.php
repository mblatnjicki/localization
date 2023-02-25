<?php

namespace app\controllers;

use yii\rest\ActiveController;
use app\models\LocalizationTranslation;

class LocalizationTranslationController extends ActiveController
{
    public $modelClass = 'app\models\LocalizationTranslation';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['update']);
        return $actions;
    }

    public function actionUpdate()
    {
        $postData = \Yii::$app->request->post();
        $translationData = $postData['translations'];
        
        foreach($translationData as $translation){
            //get one and if exists update, otherwise save new row
            $existingRow = LocalizationTranslation::findOne([
                    'localization_key_id' => $translation['localization_key_id'], 
                    'language_id' => $translation['language_id']
                ]);
            
            if($existingRow){
                $existingRow->translation = $translation['translation'];
                $existingRow->save();
            } else {
                $translationRow = new LocalizationTranslation();
                $translationRow->localization_key_id = $translation['localization_key_id'];
                $translationRow->language_id = $translation['language_id'];
                $translationRow->translation = $translation['translation'];

                $translationRow->save();
            }
        }

        return [];
    }
}