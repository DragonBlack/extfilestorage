<?php
/**
 * Frontend скрипты для доступа к API Dropbox
 */

namespace DragonBlack\extfilestorage;


use yii\web\AssetBundle;

class DropboxAsset extends AssetBundle {
    public $js = [
        [
            'https://www.dropbox.com/static/api/2/dropins.js',
            'id' => 'dropboxjs',
        ],
        'extfsdropbox.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
    
    public function init() {
        $this->sourcePath = __DIR__.'/assets';
        $this->js[0]['data-app-key'] = \Yii::$app->params['efsdropbox_appkey'];
        parent::init();
    }
}