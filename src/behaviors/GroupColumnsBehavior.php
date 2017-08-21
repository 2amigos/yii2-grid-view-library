<?php

/*
 * This file is part of the 2amigos/yii2-grid-view-library project.
 * (c) 2amigOS! <http://2amigos.us/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace dosamigos\grid\behaviors;

use Closure;
use yii\base\Behavior;
use yii\grid\DataColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class GroupColumnsBehavior extends Behavior
{
    const MERGE_SIMPLE = 'simple';
    const MERGE_NESTED = 'nested';
    const MERGE_FIRST_ROW = 'firstrow';
    const POS_ABOVE = 'above';
    const POS_BELOW = 'below';

    /**
     * @var array the column names to merge
     */
    public $mergeColumns = [];
    /**
     * @var string the way to merge the columns. Possible values are:
     *
     * - [[GroupGridView::MERGE_SIMPLE]] Column values are merged independently
     * - [[GroupGridView::MERGE_NESTED]] Column values are merged if at least one value of nested columns changes
     *   (makes sense when several columns in $mergeColumns option)
     * - [[GroupGridView::MERGE_FIRST_ROW]] Column values are merged independently, but value is shown in first row of
     *   group and below cells just cleared (instead of `rowspan`)
     *
     */
    public $type = self::MERGE_SIMPLE;
    /**
     * Need to merge null values in columns? Default - merge on.
     * @var boolean
     */
    public $doNotMergeEmptyValue = false;
    /**
     * Exclude column for the rule if [[GroupGridView::doNotMergeEmptyValue]] is true.
     * @var array
     */
    public $mergeEmptyColumns = [];
    /**
     * @var string the CSS class to use for the merged cells
     */
    public $mergeCellClass = 'group-view-merge-cells';
    /**
     * @var array the list of columns on which change extra row will be triggered
     */
    public $extraRowColumns = [];
    /**
     * @var string|\Closure an anonymous function that returns the value to be displayed for every extra row.
     *                      The signature of this function is `function ($model, $key, $index, $totals)`.
     *                      If this is not set, `$model[$attribute]` will be used to obtain the value.
     *
     * You may also set this property to a string representing the attribute name to be displayed in this column.
     * This can be used when the attribute to be displayed is different from the [[attribute]] that is used for
     * sorting and filtering.
     */
    public $extraRowValue;
    /**
     * @var string the position of the extra row. Possible values are [[GroupGridView::POS_ABOVE]] or
     *             [[GroupGridView::POS_BELOW]]
     */
    public $extraRowPosition = self::POS_ABOVE;
    /**
     * @var \Closure an anonymous function that returns a calculated value of the totals. Its signature is
     *               `function($model, $index, $totals)`
     */
    public $extraRowTotalsValue;
    /**
     * @var string the CSS class to add on the extra row TD tag
     */
    public $extraRowClass = 'group-view-extra-row';
    /**
     * @var array stores the groups
     */
    private $_groups = [];

    /**
     * Renders the data models for the grid view.
     */
    public function renderItems()
    {
        /** @var GridView $grid */
        $grid = $this->owner;
        $caption = $grid->renderCaption();
        $columnGroup = $grid->renderColumnGroup();
        $tableHeader = $grid->showHeader ? $grid->renderTableHeader() : false;
        $tableBody = $this->renderTableBody();
        $tableFooter = $grid->showFooter ? $grid->renderTableFooter() : false;
        $content = array_filter(
            [
                $caption,
                $columnGroup,
                $tableHeader,
                $tableFooter,
                $tableBody,
            ]
        );

        return Html::tag('table', implode("\n", $content), $grid->tableOptions);
    }

    /**
     * Renders the table body.
     * @return string the rendering result.
     */
    public function renderTableBody()
    {
        if (!empty($this->mergeColumns) || !empty($this->extraRowColumns)) {
            $this->groupColumns();
        }
        /** @var GridView $grid */
        $grid = $this->owner;
        $models = array_values($grid->dataProvider->getModels());
        $keys = $grid->dataProvider->getKeys();
        $rows = [];
        foreach ($models as $index => $model) {
            $key = $keys[$index];
            if ($grid->beforeRow !== null) {
                $row = call_user_func($grid->beforeRow, $model, $key, $index, $grid);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }

            $rows[] = $this->renderTableRow($model, $key, $index);

            if ($grid->afterRow !== null) {
                $row = call_user_func($grid->afterRow, $model, $key, $index, $grid);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }
        }

        if (empty($rows) && $grid->emptyText !== false) {
            $colspan = count($grid->columns);

            return "<tbody>\n<tr><td colspan=\"$colspan\">" . $grid->renderEmpty() . "</td></tr>\n</tbody>";
        }
        return "<tbody>\n" . implode("\n", $rows) . "\n</tbody>";
    }

    /**
     * Finds and stores changes of grouped columns
     */
    public function groupColumns()
    {
        /** @var GridView $grid */
        $grid = $this->owner;
        $models = $grid->dataProvider->getModels();

        if (count($models) == 0) {
            return;
        }

        $this->mergeColumns = (array)$this->mergeColumns;
        $this->extraRowColumns = (array)$this->extraRowColumns;

        // store columns for group.
        $columns = array_unique(ArrayHelper::merge($this->mergeColumns, $this->extraRowColumns));
        foreach ($columns as $key => $name) {
            foreach ($grid->columns as $column) {
                if (property_exists($column, 'attribute') && ArrayHelper::getValue($column, 'attribute') == $name) {
                    $columns[$key] = $column;
                } elseif (in_array($name, $this->extraRowColumns)) {
                    $columns[$key] = new DataColumn(['attribute' => $name]);
                }
            }
        }

        $groups = [];

        // values for the first row
        $values = $this->getRowValues($columns, $models[0]);

        foreach ($values as $key => $value) {
            $groups[$key][] = [
                'value' => $value,
                'column' => $key,
                'start' => 0
            ];
        }

        // calculate totals for the first row
        $totals = [];

        // iterate through models
        foreach ($models as $index => $model) {
            // save row values in array
            $rowValues = $this->getRowValues($columns, $model, $index);

            // define if any change occurred. Need this extra foreach for correctly process extraRows
            $changedColumns = [];
            foreach ($rowValues as $name => $value) {
                $previous = end($groups[$name]);
                if ($this->doNotMergeEmptyValue && empty($value) && !in_array($name, $this->mergeEmptyColumns, true)) {
                    $changedColumns[] = $name;
                } elseif ($value != $previous['value']) {
                    $changedColumns[] = $name;
                }
            }

            // if this flag is true we will write change for all grouped columns. Its required when a change of any
            // column from extraRowColumns occurs
            $extraRowColumnChanged = (count(array_intersect($changedColumns, $this->extraRowColumns)) > 0);

            // this changeOccured related to foreach below. It is required only for mergeType == self::MERGE_NESTED,
            // to write change for all nested columns when change of previous column occurred
            $changeOccurred = false;
            foreach ($rowValues as $columnName => $columnValue) {
                // value changed
                $valueChanged = in_array($columnName, $changedColumns);
                //change already occured in this loop and mergeType set to MERGE_NESTED
                $saveChange = $valueChanged || ($changeOccurred && $this->type == self::MERGE_NESTED);

                if ($extraRowColumnChanged || $saveChange) {
                    $changeOccurred = true;
                    $lastIndex = count($groups[$columnName]) - 1;

                    //finalize prev group
                    $groups[$columnName][$lastIndex]['end'] = $index - 1;
                    $groups[$columnName][$lastIndex]['totals'] = $totals;

                    //begin new group
                    $groups[$columnName][] = [
                        'start' => $index,
                        'column' => $columnName,
                        'value' => $columnValue,
                    ];
                }
            }

            if ($extraRowColumnChanged) {
                $totals = [];
            }
            $totals = $this->getExtraRowTotals($model, $index, $totals);
        }

        // finalize group for last row
        foreach ($groups as $name => $value) {
            $lastIndex = count($groups[$name]) - 1;
            $groups[$name][$lastIndex]['end'] = count($models) - 1;
            $groups[$name][$lastIndex]['totals'] = $totals;
        }

        $this->_groups = $groups;
    }

    /**
     * @inheritdoc
     */
    public function renderTableRow($model, $key, $index)
    {
        $rows = [];
        /** @var GridView $grid */
        $grid = $this->owner;

        if ($grid->rowOptions instanceof Closure) {
            $options = call_user_func($grid->rowOptions, $model, $key, $index, $this);
        } else {
            $options = $grid->rowOptions;
        }
        $options['data-key'] = is_array($key) ? json_encode($key) : (string)$key;

        $cells = [];
        /** @var \yii\grid\Column $column */
        foreach ($grid->columns as $column) {
            $name = property_exists($column, 'attribute') ? ArrayHelper::getValue($column, 'attribute') : '' ;

            $isGroupColumn = in_array($name, $this->mergeColumns);

            if (!$isGroupColumn) {
                $cells[] = $column->renderDataCell($model, $key, $index);
                continue;
            }

            $edge = $this->isGroupEdge($name, $index);

            switch ($this->type) {
                case static::MERGE_SIMPLE:
                case static::MERGE_NESTED:
                    if (isset($edge['start'])) {
                        $column->contentOptions['rowspan'] = $edge['group']['end'] - $edge['group']['start'] + 1;
                        Html::addCssClass($column->contentOptions, $this->mergeCellClass);
                        $cells[] = $column->renderDataCell($model, $key, $index);
                    }
                    break;
                case static::MERGE_FIRST_ROW:
                    $cells[] = isset($edge['start']) ? $column->renderDataCell($model, $key, $index) : '<td></td>';
            }
        }

        $rows[] = Html::tag('tr', implode('', $cells), $options);

        if (count($this->extraRowColumns)) {
            $extraRowEdge = $this->isGroupEdge($this->extraRowColumns[0], $index);
            if ($this->extraRowPosition == static::POS_ABOVE && isset($extraRowEdge['start'])) {
                array_unshift($rows, $this->renderExtraRow($model, $key, $index, $extraRowEdge['group']['totals']));
            }

            if ($this->extraRowPosition == static::POS_BELOW && isset($extraRowEdge['end'])) {
                $rows[] = $this->renderExtraRow($model, $key, $index, $extraRowEdge['group']['totals']);
            }
        }

        return implode('', $rows);
    }

    /**
     * Renders extra row when required
     *
     * @param  mixed $model
     * @param  mixed $key
     * @param  int $index
     * @param  number $totals
     *
     * @return string the extra row
     */
    protected function renderExtraRow($model, $key, $index, $totals)
    {
        /** @var GridView $grid */
        $grid = $this->owner;

        if ($this->extraRowValue instanceof Closure) {
            $content = call_user_func($this->extraRowValue, $model, $key, $index, $totals);
        } else {
            $values = [];
            foreach ($this->extraRowColumns as $name) {
                $values[] = ArrayHelper::getValue($model, $name);
            }

            $content = '<strong>' . implode(' :: ', $values) . '</strong>';
        }

        $colspan = count($grid->columns);

        $cell = Html::tag('td', $content, ['class' => $this->extraRowClass, 'colspan' => $colspan]);

        return Html::tag('tr', $cell);
    }

    /**
     * Is current row start or end of group in particular column
     *
     * @param  string $name the column name
     * @param  int $row the row index
     *
     * @return array
     */
    protected function isGroupEdge($name, $row)
    {
        $result = [];
        foreach ($this->_groups[$name] as $column) {
            if ($column['start'] == $row) {
                $result['start'] = $row;
                $result['group'] = $column;
            }
            if ($column['end'] == $row) {
                $result['end'] = $row;
                $result['group'] = $column;
            }
            if (count($result)) {
                break;
            }
        }

        return $result;
    }

    /**
     * If there is a Closure will return the newly calculated totals on the Closure according to the code specified by
     * the user.
     *
     * @param  mixed $model the data model being rendered
     * @param  int $index the zero-based index of the data model among the models array
     * @param  array $totals the calculated totals by the Closure
     *
     * @return array|mixed
     */
    protected function getExtraRowTotals($model, $index, $totals)
    {
        return $this->extraRowTotalsValue instanceof Closure
            ? call_user_func($this->extraRowTotalsValue, $model, $index, $totals)
            : [];
    }

    /**
     * Returns the row values of the column
     *
     * @param  array $columns the columns
     * @param  mixed $model the model
     * @param  int $index the zero-base index of the model among the models array
     *
     * @return array
     */
    protected function getRowValues($columns, $model, $index = 0)
    {
        /** @var GridView $grid */
        $grid = $this->owner;
        $values = [];
        $keys = $grid->dataProvider->getKeys();
        foreach ($columns as $column) {
            /** @var \yii\grid\DataColumn $column */
            if ($column instanceof DataColumn) { // we only work with DataColumn types
                $values[$column->attribute] = $this->getColumnDataCellContent($column, $model, $keys[$index], $index);
            }
        }

        return $values;
    }

    /**
     * Returns the column data cell content
     *
     * @param  \yii\grid\DataColumn $column
     * @param  mixed $model the data model being rendered
     * @param  mixed $key the key associated with the data model
     * @param  int $index the zero-based index of the data model among the models array returned by [[GridView::dataProvider]]
     *
     * @return string               the rendering result
     */
    protected function getColumnDataCellContent($column, $model, $key, $index)
    {
        /** @var GridView $grid */
        $grid = $this->owner;
        if ($column->content === null) {
            return $grid->formatter->format(
                $this->getColumnDataCellValue($column, $model, $key, $index),
                $column->format
            );
        }
        if ($column->content !== null) {
            return call_user_func($column->content, $model, $key, $index, $column);
        }

        return $grid->emptyCell;
    }

    /**
     * Returns the column data cell value
     *
     * @param  \yii\grid\DataColumn $column
     * @param  mixed $model the data model being rendered
     * @param  mixed $key the key associated with the data model
     * @param  int $index the zero-based index of the data model among the models array
     *
     * @return mixed|null           the result
     */
    protected function getColumnDataCellValue($column, $model, $key, $index)
    {
        if ($column->value !== null) {
            if (is_string($column->value)) {
                return ArrayHelper::getValue($model, $column->value);
            }

            return call_user_func($column->value, $model, $index, $column);
        } elseif ($column->attribute !== null) {
            return ArrayHelper::getValue($model, $column->attribute);
        }

        return null;
    }
}
