<?php
/**
 * This is the template for generating CRUD model class of the specified model.
 */

use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator \jinowom\jajaxcrud\generators\crud\Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$formModelClass = StringHelper::basename($generator->formModelClass);
if ($modelClass === $formModelClass) {
    $modelAlias = $modelClass . 'Model';
}

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->formModelClass, '\\')) ?>;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use <?= ltrim($generator->modelClass, '\\') . (isset($modelAlias) ? " as $modelAlias" : "") ?>;

/**
* <?= $formModelClass ?> represents the model behind the search form about `<?= $generator->modelClass ?>`.
*/
class <?= $formModelClass ?> extends <?= isset($modelAlias) ? $modelAlias : $modelClass ?>

{
    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels = ArrayHelper::merge($attributeLabels, []);
        return $attributeLabels;
    }

    public function rules()
    {
        $rules = parent::rules();
        /*foreach ($rules as $k => $v) {
            if ($v[1] == 'required'){
                $rules[$k][0] = array_diff($rules[$k][0], ['created_at', 'updated_at', 'created_by', 'updated_by']);
            }
        }*/
        $rules = ArrayHelper::merge($rules, []);
        return $rules;
    }
}
