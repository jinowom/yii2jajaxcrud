<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \jinowom\jajaxcrud\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->formModelClass, '\\') ?> */
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->formModelClass)) ?>-view">
    <div class="row">
        <div class="col-sm-12"><?="<?= \$this->render(\"_detail-view\", ['model' => \$model]) ?>" ?></div>
        <div class="col-sm-12"></div>
    </div>
</div>
