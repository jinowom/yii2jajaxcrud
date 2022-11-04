<?php
/**
 * This is the template for generating CRUD search class of the specified model.
 */

use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator \jinowom\jajaxcrud\generators\crud\Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $modelAlias = $modelClass . 'Model';
}
$rules = $generator->generateSearchRules();
$labels = $generator->generateSearchLabels();
$editableFields = $generator->generateEditableFields();
$thumbImageFields = $generator->generateThumbImageFields();
foreach ($editableFields as $k => $v) {
    $editableFields[$k] = "'{$v}'";
}
$searchAttributes = $generator->getSearchAttributes();
$searchConditions = $generator->generateSearchConditions();

$pks = $generator->modelClass::primaryKey();
if ($generator->isDesc){
    $defaultSort = "SORT_DESC";
}else{
    $defaultSort = "SORT_ASC";
}
if (count($pks) === 1) {
    $pk = $pks[0];
    $defaultOrder = "['$pk' => $defaultSort, ]";
} else {
    $defaultOrder = "[";
    foreach ($pks as $pk) {
        $defaultOrder .= "'$pk' => $defaultSort, ";
    }
    $defaultOrder .= "]";
}

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->searchModelClass, '\\')) ?>;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use <?= ltrim($generator->modelClass, '\\') . (isset($modelAlias) ? " as $modelAlias" : "") ?>;

/**
 * <?= $searchModelClass ?> represents the model behind the search form about `<?= $generator->modelClass ?>`.
 */
class <?= $searchModelClass ?> extends <?= isset($modelAlias) ? $modelAlias : $modelClass ?>

{
    const EMPTY_STRING = "(空字符)";
    const NO_EMPTY = "(非空)";
    const SCENARIO_EDITABLE = 'editable';

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_EDITABLE => [<?=implode(',', $editableFields) ?>],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            <?= implode(",\n            ", $rules) ?>,
        ];
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find();
        $this->load($params);
        <?= implode("        ", $searchConditions) ?>
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            <?php if($generator->isDesc): ?>'sort' => ['defaultOrder' => <?=$defaultOrder ?>],
            <?php else: ?>'sort' => ['defaultOrder' => <?=$defaultOrder ?>],
        <?php endif; ?>]);
        if (!$this->validate()) {
            return $dataProvider;
        }
        return $dataProvider;
    }

    /**
     * @param ActiveQuery $query
     * @param $attribute
     */
    protected function _rangeFilter(&$query, $attribute, $isDate = false)
    {
        if ( ! is_null($this->$attribute) && strpos($this->$attribute, ' - ') !== false ) {
            list($s, $e) = explode(' - ', $this->$attribute);
            if ($isDate){
                $s = strtotime($s);
                $e = strtotime($e);
            }
            if ($s)$query->andFilterWhere(['>=', $attribute, $s]);
            if ($e)$query->andFilterWhere(['<=', $attribute, $e]);
        }
    }

    /**
     * @param ActiveQuery $query
     * @param $attribute
     */
    protected function _fieldFilter(&$query, $field, $attribute, $filter_type)
    {
        $this->$attribute = trim($this->$attribute);
        switch($this->$attribute){
            case \Yii::t('yii', '(not set)'):
                $query->andFilterWhere(['IS', $field, new Expression('NULL')]);
                break;
            case self::EMPTY_STRING:
                $query->andWhere([$field => '']);
                break;
            case self::NO_EMPTY:
                $query->andWhere(['IS NOT', $field, new Expression('NULL')])->andWhere(['<>', $field, '']);
                break;
            default:
                $query->andFilterWhere([$filter_type, $field, $this->$attribute]);
                break;
        }
    }
}
