<?php

/*
 * This file is part of the 2amigos/yii2-grid-view-library project.
 * (c) 2amigOS! <http://2amigos.us/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace dosamigos\grid\columns;

use yii\bootstrap\Html;

class BooleanColumn extends DataColumn
{
    /**
     * @var string $onTrue the contents to display when value is true.
     */
    public $onTrue = '<span class="glyphicon glyphicon-ok text-success"></span>';
    /**
     * @var string $onFalse the contents to display when value is false.
     */
    public $onFalse = '<span class="glyphicon glyphicon-remove text-danger"></span>';
    /**
     * @inheritdoc
     */
    public $format = 'html';
    /**
     * @var bool whether to display empty values with the $onFalse contents.
     */
    public $treatEmptyAsFalse = false;

    /**
     * @inheritdoc
     */
    public function renderDataCell($model, $key, $index)
    {
        if ($this->contentOptions instanceof \Closure) {
            $options = call_user_func($this->contentOptions, $model, $key, $index, $this);
        } else {
            $options = $this->contentOptions;
        }

        Html::addCssClass($options, 'text-center');
        return Html::tag('td', $this->renderDataCellContent($model, $key, $index), $options);
    }

    /**
     * @inheritdoc
     */
    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);
        if (!empty($value)) {
            return $value ? $this->onTrue : $this->onFalse;
        }

        return $this->treatEmptyAsFalse ? $this->onFalse : $value;
    }
}
