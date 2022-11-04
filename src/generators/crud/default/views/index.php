<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $generator \jinowom\jajaxcrud\generators\crud\Generator */
$modelClass = StringHelper::basename($generator->formModelClass);
$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$actionParams = $generator->generateActionParams();

$editableFields = $generator->generateEditableFields();
$dateRangeFields = $generator->generateDateRangeFields();
$rangeFields = $generator->generateRangeFields();
$thumbImageFields = $generator->generateThumbImageFields();
$statusField = $generator->statusField;

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

$pks = $generator->modelClass::primaryKey();
$pk = $pks[0];

echo "<?php\n";
?>
use kartik\grid\GridView;
use kartik\grid\DataColumn;
use kartik\grid\SerialColumn;
use kartik\grid\EditableColumn;
use kartik\grid\CheckboxColumn;
use kartik\grid\ExpandRowColumn;
use kartik\grid\EnumColumn;
use kartik\grid\ActionColumn;
use kartik\grid\FormulaColumn;
use kartik\daterange\DateRangePicker;
use jinowom\jajaxcrud\rangecolumn\RangeColumn;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use jinowom\jajaxcrud\CrudAsset;
use jinowom\jajaxcrud\BulkButtonWidget;
use jinowom\yii2jtools\tools\JsBlock;
use yii\web\JsExpression;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->formModelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->formModelClass)) ?>-index">
    <div id="ajaxCrudDatatable">
        <?="<?= "?>GridView::widget([
            'id' => 'crud-datatable',
            'rowOptions' => [
                'class' => 'gvRowBaguetteBox',
            ],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'responsive' => true,
            'showPageSummary' => true,
            'pjax' => true,
            'hover' => true,
            'striped' => true,
            'condensed' => true,
            'columns' => [
                [
                    'class' => CheckboxColumn::class,
                    'width' => "20px",
                ],
                [
                    'class' => SerialColumn::class,
                    'width' => "40px",
                    'pageSummary' => "合计",
                ],
                /*[
                    'class' => ExpandRowColumn::class,
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'detail' => function ($model, $key, $index, $column) {
                        return $this->render('view', ['model' => $model]);
                    },
                    'expandOneOnly' => true,
                ],*/
                <?php foreach ($generator->getColumnNames() as $name): ?><?php if(in_array($name, $editableFields)): ?>[
                    'class' => EditableColumn::class,
                    'attribute' => "<?=$name ?>",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'readonly' => function ($model, $key, $index, $widget) {
                        return false;
                    },
                    'editableOptions' => function ($model, $key, $index, $widget) {
                        return [
                            'header' => "修改",
                            'size' => "md",
                            'formOptions' => ['action' => ['editable-edit']],
                        ];
                    },
                    'refreshGrid' => true,
                ],
                <?php elseif (in_array($name, $dateRangeFields)): ?>[
                    'class' => DataColumn::class,
                    'attribute' => "<?=$name ?>",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'format' => ['date', 'php:Y-m-d H:i:s'],
                    'filter' => DateRangePicker::widget([
                        'model' => $searchModel,
                        'attribute' => "<?=$name ?>",
                        'convertFormat' => true,
                        'pluginOptions' => [
                            'opens' => "center",
                            'timePicker' => true,
                            'timePicker24Hour' => true,
                            'timePickerSeconds' => true,
                            'showWeekNumbers' => true,
                            'showDropdowns' => true,
                            'timePickerIncrement' => 1,
                            'locale' => [
                                'format' => "Y-m-d H:i:s",
                                'applyLabel' => "确认",
                                'cancelLabel' => "清除",
                                'fromLabel' => "开始时间",
                                'toLabel' => "结束时间",
                                'daysOfWeek' => ["日","一","二","三","四","五","六"],
                                'monthNames' => ["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
                            ],
                        ],
                        'presetDropdown' => true,
                        'autoUpdateOnInit' => false,
                        'useWithAddon' => true,
                        'pjaxContainerId' => "crud-datatable-pjax",
                        'pluginEvents' => [
                            'cancel.daterangepicker' => new JsExpression("function(ev, picker) {let e13=$.Event('keydown');e13.keyCode=13;let _input=$(this);if(!$(this).is('input')){_input=$(this).parent().find('input:hidden');}_input.val('').trigger(e13);}"),
                        ],
                    ]),
                ],
                <?php elseif (in_array($name, $rangeFields)): ?>[
                    'class' => RangeColumn::class,
                    'attribute' => "<?=$name ?>",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                ],
                <?php elseif (in_array($name, $thumbImageFields)): ?>[
                    'class' => DataColumn::class,
                    'attribute' => "<?=$name ?>",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'mergeHeader' => true,
                    'enableSorting' => false,
                    'format' => 'raw',
                    'value' => function ($m) {
                        return $m-><?=$name ?>?Html::a(Html::img($m-><?=$name ?>, ['alt' => '缩略图', 'width' => 120]), $m-><?=$name ?>):'';
                    },
                ],
                <?php elseif ($name == $statusField): ?>[
                    'class' => EnumColumn::class,
                    'attribute' => "<?=$name ?>",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'enum' => $searchModel->statusDesc,
                ],
                <?php else: ?>[
                    'class' => DataColumn::class,
                    'attribute' => "<?=$name ?>",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                ],
                <?php endif; ?><?php endforeach; ?>[
                    'class' => ActionColumn::class,
                    'dropdown' => false,
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'urlCreator' => function($action, $model, $key, $index) {
                        return Url::to([$action,'<?=substr($actionParams,1)?>' => $key, 'type' => "soft"]);
                    },
                    'viewOptions' => ['role' => "modal-remote", 'title' => "View",'data-toggle' => "tooltip"],
                    'updateOptions' => ['role' => 'modal-remote', 'title' => "Update", 'data-toggle' => "tooltip"],
                    'deleteOptions' => [
                        'role' => 'modal-remote',
                        'title' => "删除",
                        'data-confirm' => false,
                        'data-method' => false, // for overide yii data api
                        'data-request-method' => "post",
                        'data-toggle' => "tooltip",
                        'data-confirm-title' => "删除数据提示!",
                        'data-confirm-message' => "你确认要删除本条数据吗?",
                    ],
                ],
                [
                    'class' => ActionColumn::class,
                    'header' => '其他操作',
                    'template' => '{test}',
                    'mergeHeader' => true,
                    'buttons' => [
                        'test' => function ($url, $model, $key) {
                            return Html::a('Test', $url, [
                                'title' => Yii::t('yii', 'Test'),
                                'aria-label' => Yii::t('yii', 'Test'),
                                'data-pjax' => '0',
                                'role' => 'modal-remote',
                                'data-toggle' => 'tooltip',
                            ]);
                        },
                    ],
                    'contentOptions' => ['style' => 'vertical-align: middle;'],
                ],
            ],
            'toolbar' => [
                ['content' =>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role' => "modal-remote", 'title' => "新建 <?= Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>", 'class' => "btn btn-default"]).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax' => 1, 'class' => "btn btn-default", 'title' => "Reset Grid"]).
                    '{toggleData}'.
                    '{export}'
                ],
            ],
            'panel' => [
                'type' => "primary", 
                'heading' => "<i class=\"glyphicon glyphicon-list\"></i> <?= Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?> 列表",
                'before' => "<em>* 你可以拖动改变单列的宽度；筛选框输入<code>" . \Yii::t('yii', '(not set)'). "</code>会只搜索值为空的数据；筛选框输入<code>" . $searchModel::EMPTY_STRING . "</code>会只搜索值为空字符的数据；筛选框输入<code>" . $searchModel::NO_EMPTY . "</code>会只搜索非空数据。</em>",
                'after' => BulkButtonWidget::widget([
                    'buttons' => Html::a('<i class="glyphicon glyphicon-trash"></i> 删除选择', ["bulkdelete", 'type' => "soft"], [
                        "class" => "btn btn-danger btn-xs",
                        'role' => "modal-remote-bulk",
                        'data-confirm' => false, 'data-method' => false,// for overide yii data api
                        'data-request-method' => "post",
                        'data-confirm-title' => "删除数据提示!",
                        'data-confirm-message' => "你确认要删除这些数据吗?"
                    ])." ".
                    Html::a('<i class="glyphicon glyphicon-wrench"></i> test选择', ["bulktest"], [
                        "class" => "btn btn-warning btn-xs",
                        'role' => "modal-remote-bulk",
                        'data-confirm' => false, 'data-method' => false,
                        'data-request-method' => "post",
                        'data-confirm-title' => "test数据提示!",
                        'data-confirm-message' => "你确认要test这些数据吗?"
                    ]),
                ]).
                '<div class="clearfix"></div>',
            ]
        ])<?=" ?>\n"?>
    </div>
</div>
<?='<?php Modal::begin([
    \'id\' => "ajaxCrudModal",
    \'size\' => Modal::SIZE_LARGE,
    \'footer\' => "", // always need it for jquery plugin
]); ?>'."\n"?>
<?='<?php Modal::end(); ?>'?>


<?='<?php JsBlock::begin(); ?>' ?>

<?='<script>' ?>

<?='$(function () {
    baguetteBox.run(".gvRowBaguetteBox", {
        animation: "fadeIn"
    });
})' ?>

<?='</script>' ?>

<?='<?php JsBlock::end(); ?>' ?>

