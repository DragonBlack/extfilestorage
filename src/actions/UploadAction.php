<?php
/**
 * Created by PhpStorm.
 * User: serg
 * Date: 03.08.16
 * Time: 15:46
 */

namespace dragonblack\extfilestorage\actions;


use yii\base\Action;
use yii\helpers\Json;

class UploadAction extends Action {
    public $targetFS = 'local';
    
    public $targetFSComponent;
    
    public function run(){
        return Json::encode(['error'=>false]);
    }
}