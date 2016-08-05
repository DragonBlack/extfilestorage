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

class DropboxWidget extends Widget {
    /** @var  string  Название asset класса */
    public $asset = 'dropbox';

    /** @var array Настройки элемента управления */
    public $elementControl = [];

    /** @var  string|array  URL для отправки файлов на сервер */
    public $url;

    /** @var  string  JS код обработки списка файлов из внешнего хранилища */
    public $success;

    /** @var  string  JS код, выполняющийся в случае отказа пользователя от выбора */
    public $cancel;

    /** @var string  Тип получаемых ссылок: для скачивания (direct) или для предпросмотра (preview)*/
    public $linktype = 'direct';

    /** @var  string  JS код, выполняемый после отправки файлов на сервер */
    public $afterUpload;

    /** @var bool Разрешить или запретить множественный выбор */
    public $multiselect = true;

    /** @var array Допустимые расширения или группы типов файлов */
    public $extensions = [];
    
    /** @var  string  Имя asset-класса  */
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
        $view->registerJs("linktype = '{$this->linktype}';");
        if(!empty($this->success)){
            $view->registerJs("successFunc['".$elementId."'] = {$this->success};");
        }
        if(!empty($this->cancel)){
            $view->registerJs("cancelFunc['".$elementId."'] = {$this->cancel};");
        }
        if(!empty($this->afterUpload)){
            $view->registerJs("afterUpload['".$elementId."'] = ".(new JsExpression($this->afterUpload)).";");
        }

        $view->registerJs("multiselect = ".($this->multiselect ? 'true' : 'false'));
        $view->registerJs("extensions = ".Json::encode($this->extensions));

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