<?php

class WheelModule extends CWebModule
{
    private $_assetsUrl;

    public $_awardList = array(
        3 => array('prize' => array(90,30, 150, 270), 'lostPrize' => array(0, 60, 120, 180, 210, 240, 300, 330, 360))
    );

    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application

        // import the module-level models and components
        $this->setImport(array(
            'wheel.models.*',
            'wheel.components.*',
        ));
    }

    public function getAwardList(){
        return $this->_awardList;
    }

    public function getAssetsUrl()
    {
        if ($this->_assetsUrl === null)
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish(
                Yii::getPathOfAlias('wheel.assets'));
        return $this->_assetsUrl;
    }

    public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        } else
            return false;
    }
}
