<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \jinowom\jajaxcrud\generators\crud\Generator */

echo "<?php\n";
?>

use jinowom\yii2jtools\tools\ArrayHelper;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->formModelClass, '\\') ?> */
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->formModelClass)) ?>-_detail-view">

    <?= "<?= " ?>DetailView::widget([
        'model' => $model,
        'condensed' => true,
        'hover' => true,
        'enableEditMode' => false,
        'panel' => [
            'heading' => "详细",
            'type' => DetailView::TYPE_INFO,
        ],
        'attributes' => [
<?php
            if (($tableSchema = $generator->getTableSchema()) === false) {
                foreach ($generator->getColumnNames() as $name) {
                    echo "            '" . $name . "',\n";
                }
            } else {
                foreach ($generator->getTableSchema()->columns as $column) {
                    $format = $generator->generateColumnFormat($column);
                    echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                }
            }
            ?>
        ],
    ]) ?>

</div>