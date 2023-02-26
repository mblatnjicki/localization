<?php
namespace app\components;

use yii\base\Component;

class ExportConversionComponent extends Component{
    
    public function convertToCsvFormat($languages, $keys, $translations){
        $data = [];

        $headers = ['Key'];
        foreach($languages as $language){
            $headers[] = $language->name;
        }

        $data[] = $headers;

        foreach($keys as $key){
            $row = [$key->key];

            foreach($languages as $language){
                $found = false;
                foreach($translations as $translation){
                    if($translation->language_id == $language->id && $translation->localization_key_id == $key->id){
                        $row[] = $translation->translation;
                        $found = true;
                        break;
                    }
                }

                if(!$found){
                    $row[] = '';
                }
            }
            $data[] = $row;
        }

        return $data;
        
    }

    public function convertToJsonFormat($languages, $keys, $translations){

        $data = [];
        $keysAssoc = [];
        $languageAssoc = [];

        foreach($keys as $key){
            $keysAssoc[$key->id] = $key->key;
        }

        foreach($languages as $language){
            $languageAssoc[$language->id] = $language->name;
        }


        foreach($translations as $translation){
            if(!isset($data[$languageAssoc[$translation->language_id]])){
                $data[$languageAssoc[$translation->language_id]] = [];
            }
            $data[$languageAssoc[$translation->language_id]][$keysAssoc[$translation->localization_key_id]] = $translation->translation;
        }

        return $data;
    }

    public function exportToCsvFile($data)
    {
        $file = fopen('/tmp/localization.csv', 'w');

        foreach($data as $row){
            fputcsv($file, $row);
        }

        return ['/tmp/localization.csv'];
    }

    public function exportToJsonFiles($data)
    {
        $files = [];

        foreach($data as $key=>$object){
            file_put_contents('/tmp/' . $key . '.json', json_encode($object));

            $files[] = '/tmp/' . $key . '.json';
        }

        return $files;
    }
}
?>