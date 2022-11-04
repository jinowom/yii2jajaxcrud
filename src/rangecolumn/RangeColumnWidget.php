<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/6/12
 * Time: 14:24
 */

namespace jinowom\jajaxcrud\rangecolumn;


use kartik\base\Widget;

class RangeColumnWidget extends Widget
{
    public $model;
    public $attribute;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('index', [
            'widget' => $this,
        ]);
    }
}