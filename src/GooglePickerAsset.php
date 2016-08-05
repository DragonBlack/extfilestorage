<?php
/**
 * Created by PhpStorm.
 * User: serg
 * Date: 04.08.16
 * Time: 17:14
 */

namespace DragonBlack\extfilestorage;


use yii\web\AssetBundle;

class GooglePickerAsset extends AssetBundle {
    public $js = [
        'https://apis.google.com/js/api.js',
        'https://apis.google.com/js/client.js',
        'extfspicker.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init() {
        $this->sourcePath = __DIR__.'/assets';
        parent::init();
    }

}