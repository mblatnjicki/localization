<?php

namespace app\controllers;

use yii\rest\ActiveController;
use app\models\LocalizationTranslation;
use app\models\LocalizationKey;
use app\models\Language;

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

    public function actionExport()
    {
        $postData = \Yii::$app->request->post();
        $type = $postData['type'];
        
        $languages = Language::find()->all();
        $keys = LocalizationKey::find()->all();
        $translations = LocalizationTranslation::find()->all();

        $data = [];
        $filePaths = [];

        switch($type){
            case 'csv':
                $data = \Yii::$app->exportConversion->convertToCsvFormat($languages, $keys, $translations);
                $filePaths = \Yii::$app->exportConversion->exportToCsvFile($data);
                break;
            case 'json':
                $data = \Yii::$app->exportConversion->convertToJsonFormat($languages, $keys, $translations);
                $filePaths = \Yii::$app->exportConversion->exportToJsonFiles($data);
                break;

            default:
                return null;
        }

        $zipFilename = '/tmp/localization.zip';
        $zip = new \ZipArchive();
        if ($zip->open($zipFilename, \ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('Cannot create a zip file');
        }

        foreach($filePaths as $file){
            $zip->addFile($file, $file);
        }

        $zip->close();

        return \Yii::$app->response->sendFile($zipFilename, 'localization.zip');
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);
        
        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
        ];
        
        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }
}