<?php 

namespace jinowom\jajaxcrud;

use yii\web\AssetBundle;

/**
 * @author John Martin <john.itvn@gmail.com>
 * @since 1.0
 */
class CrudAsset extends AssetBundle
{
    public $sourcePath = '@jajaxcrud/assets';

//    public $publishOptions = [
//        'forceCopy' => true,
//    ];

    public $css = [
        'ajaxcrud.css',
        'baguetteBox.min.css',
    ];

    public $js = [
        YII_ENV_DEV?'ModalRemote.js':'ModalRemote.min.js',
        YII_ENV_DEV?'ajaxcrud.js':'ajaxcrud.min.js',
        'baguetteBox.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'kartik\grid\GridViewAsset',
    ];
}
