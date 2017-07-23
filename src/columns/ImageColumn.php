<?php

/*
 * This file is part of the 2amigos/yii2-grid-view-library project.
 * (c) 2amigOS! <http://2amigos.us/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace dosamigos\grid\columns;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * ImageColumn displays an image tag. Assumes the attribute is a url to an image.
 */
class ImageColumn extends DataColumn
{
    /**
     * @var array the HTML options of the image tag
     */
    public $imgOptions = [];
    /**
     * @var string|callable $path if string will be prepended to the column's attribute. If a callable is provided, it
     *                      will call it passing the model's attribute value.
     */
    public $path;
    /**
     * @var string $emptyText renders if $attribute is null
     */
    public $emptyText = '';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!$this->format) {
            $this->format = 'html';
        }

        if (!empty($this->path)) {
            $this->path = mb_substr($this->path, -1) == '/'
                ? ''
                : '/';
        }
        return $this->path;
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $content = $this->emptyText;
        $value = ArrayHelper::getValue($model, $this->attribute);

        if (!empty($value)) {
            $value = mb_substr($value, 0, 1) == '/'
                ? mb_substr($value, 1)
                : $value;

            $src = is_callable($this->path)
                ? call_user_func_array($this->path, [$value])
                : $this->path . $value;

            $content = Html::img($src, $this->imgOptions);
        }

        return $content;
    }
}
