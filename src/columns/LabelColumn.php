<?php

/*
 * This file is part of the 2amigos/yii2-grid-view-library project.
 * (c) 2amigOS! <http://2amigos.us/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace dosamigos\grid\columns;

use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

class LabelColumn extends DataColumn
{
    /**
     * @var array $labels the configuration on how to display the different label values. Each array element key
     *            represents a label value which can be specified as a string or an array of the following structure:
     *
     * ```
     * 'active' => [
     *      'label' => 'subscribed', // string, the status label. If not provided, the key will be used as the label.
     *      'options' => [
     *          'style' => 'padding: 2px' // array, optional, the HTML attributes of the button.
     *      ]
     * ],
     * ```
     *
     * The key names of the array will be used to match against the value. If a match is found, will render a bootstrap
     * label with the options provided.
     *
     * @see http://getbootstrap.com/components/#labels
     */
    public $labels = [];
    /**
     * @var string forcely set the format to HTML.
     */
    public $format = 'html';

    /**
     * @inheritdoc
     */
    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);

        if (isset($this->labels[$value])) {
            $text = ArrayHelper::getValue($this->labels, "$value.label", $value);
            $options = ArrayHelper::getValue($this->labels, "$value.options", ['class' => 'label-default']);

            Html::addCssClass($options, 'label');

            return Html::tag('span', $text, $options);
        }

        return $value;
    }
}
