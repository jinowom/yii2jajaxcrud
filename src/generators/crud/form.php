<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator jinowom\jajaxcrud\generators\crud\Generator */

echo '<h3>General Configuration</h2>';
echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'searchModelClass');
echo $form->field($generator, 'formModelClass');
echo $form->field($generator, 'controllerClass');
echo $form->field($generator, 'viewPath');
echo $form->field($generator, 'baseControllerClass');
echo $form->field($generator, 'editableFields');
echo $form->field($generator, 'dateRangeFields');
echo $form->field($generator, 'rangeFields');
echo $form->field($generator, 'thumbImageFields');
echo $form->field($generator, 'statusField');
echo $form->field($generator, 'enableI18N')->checkbox();
echo $form->field($generator, 'isDesc')->checkbox();
echo $form->field($generator, 'messageCategory');
