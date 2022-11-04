<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/6/12
 * Time: 10:27
 */

namespace jinowom\jajaxcrud\rangecolumn;


use kartik\grid\DataColumn;

class RangeColumn extends DataColumn
{
    public function init()
    {
        parent::init();
        $this->filter = RangeColumnWidget::widget([
            'model' => $this->grid->filterModel,
            'attribute' => $this->attribute,
        ]);
    }
}