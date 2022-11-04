<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \jinowom\jajaxcrud\generators\crud\Generator */
/* @var $model \yii\db\ActiveRecord */

$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->formModelClass, '\\') ?> */
/* @var $form \kartik\form\ActiveForm */
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->formModelClass)) ?>-test">
    <div class="row">
        <div class="col-sm-12"><?="<?= \$this->render(\"_detail-view\", ['model' => \$model]) ?>" ?></div>
        <div class="col-sm-12"></div>
    </div>
    <div class="col-sm-12">
        <div><?="<?= \$this->render(\"_form\", ['model' => \$model]) ?>" ?></div>
    </div>
</div>
