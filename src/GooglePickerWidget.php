<?php
/**
 * Виджет для организации взаимодействия с внешними хранилищами файлов
 */

namespace dragonblack\extfilestorage;


use yii\base\ErrorException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

class GooglePickerWidget extends Widget {
    /** @var  string  Название asset класса */
    public $asset = 'googlePicker';

    /** @var array Настройки элемента управления */
    public $elementControl = [];

    /** @var  string|array  URL для отправки файлов на сервер */
    public $url;

    /** @var  string  JS код обработки списка файлов из внешнего хранилища */
    public $success;

    /** @var  string  JS код, выполняющийся в случае отказа пользователя от выбора */
    public $cancel;

    /** @var array  Список запрашиваемых прав */
    public $scope = ['https://www.googleapis.com/auth/drive.readonly'];

    /** @var  string  JS код, выполняемый после отправки файлов на сервер */
    public $afterUpload;

    /** @var  string  Имя asset-класса */
    private $_assetClass;

    public function init() {
        parent::init();
        if(empty($this->asset)){
            throw new ErrorException('Asset must be defined');
        }
        
        $this->_assetClass = __NAMESPACE__.'\\'.ucfirst($this->asset).'Asset';
    }
    
    public function run() {
        $view = $this->getView();
        $class = $this->_assetClass;
        $class::register($view);
        $this->url = Url::to($this->url);
        $elementId = uniqid('extfs_');
        $view->registerJs("uploadUrl['".$elementId."'] = '{$this->url}';");
        if(!empty($this->success)){
            $view->registerJs("successFunc['".$elementId."'] = {$this->success};");
        }
        if(!empty($this->cancel)){
            $view->registerJs("cancelFunc['".$elementId."'] = {$this->cancel};");
        }
        if(!empty($this->afterUpload)){
            $view->registerJs("afterUpload['".$elementId."'] = ".(new JsExpression($this->afterUpload)).";");
        }

        $view->registerJs("developerKey = '".\Yii::$app->params['efspicker_devkey']."';");
        $view->registerJs("clientId = '".\Yii::$app->params['efspicker_clientid']."';");
        $view->registerJs("scope = ".Json::encode($this->scope).";");

        $view->registerJs("$(document).on('click', '#".$elementId."', onApiLoad);");
        $element = $this->_createElement($elementId);
        return $element;
    }

    private function _createElement($id){
        if(empty($this->elementControl['tag'])){
            $this->elementControl['tag'] = 'span';
        }
        if(!isset($this->elementControl['content'])){
            $this->elementControl['content'] = $this->asset;
        }

        $options = !empty($this->elementControl['htmlOptions']) ? $this->elementControl['htmlOptions'] : [];
        $options['data-id'] = $this->asset;
        $options['id'] = $id;

        $str = Html::tag($this->elementControl['tag'], $this->elementControl['content'], $options);
        $str .= Html::endTag($this->elementControl['tag']);
        return $str;
    }
}