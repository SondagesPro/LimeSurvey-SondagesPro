<?php 

    /**
     * 
     */
    class LimeScript extends CWidget
    {
        public function run()
        {
            App()->getClientScript()->registerScriptFile(App()->getAssetManager()->publish(Yii::getPathOfAlias('ext.LimeScript.assets'). '/script.js'));
            
            $data = array();
            $data['baseUrl']                    = Yii::app()->getBaseUrl(true);
            $data['showScriptName']             = Yii::app()->urlManager->showScriptName;
            $data['urlFormat']                  = Yii::app()->urlManager->urlFormat;
            $data['adminImageUrl']              = Yii::app()->getConfig('adminimageurl');
            $data['csrfTokenName']              = CHtml::encode(Yii::app()->request->csrfTokenName);
            $data['csrfToken']                  = CHtml::encode(Yii::app()->request->csrfToken);
            $data['csrfTokenData']              = array($data['csrfTokenName']=>$data['csrfToken']);
            $data['language']                   = Yii::app()->language;
            $data['replacementFields']['path']  = App()->createUrl("limereplacementfields/index");
            $json = json_encode($data, JSON_FORCE_OBJECT);
            $script = "LS.data = $json;\n";
            App()->getClientScript()->registerScript('LimeScript', $script, CClientScript::POS_HEAD);
        }
    }

?>
