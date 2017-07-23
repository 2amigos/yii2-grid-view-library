<?php

/*
 * This file is part of the 2amigos/yii2-grid-view-library project.
 * (c) 2amigOS! <http://2amigos.us/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace dosamigos\grid\columns;

use dosamigos\editable\EditableAddressAsset;
use dosamigos\editable\EditableBootstrapAsset;
use dosamigos\editable\EditableComboDateAsset;
use dosamigos\editable\EditableDatePickerAsset;
use dosamigos\editable\EditableDateTimePickerAsset;
use dosamigos\editable\EditableSelect2Asset;
use dosamigos\editable\EditableWysiHtml5Asset;
use dosamigos\grid\bundles\EditableColumnAsset;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * EditableColumn adds X-Editable capabilities to a column
 */
class EditableColumn extends DataColumn
{
    /**
     * @var array the options for the X-editable.js plugin.
     *            Please refer to the X-editable.js plugin web page for possible options.
     * @see http://vitalets.github.io/x-editable/docs.html#editable
     */
    public $editableOptions = [];
    /**
     * @var string suffix substituted to a name class of the tag <a>
     */
    public $classSuffix;
    /**
     * @var string the url to post
     */
    public $url;
    /**
     * @var string the type of editor
     */
    public $type = 'text';

    /**
     * @var string the language of editor
     */
    public $language = null;

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        if ($this->url === null) {
            throw new InvalidConfigException("'Url' property must be specified.");
        }

        parent::init();

        if (!$this->format) {
            $this->format = 'raw';
        }

        $rel = $this->attribute . '_editable' . $this->classSuffix;
        $this->options['pjax'] = '0';
        $this->options['rel'] = $rel;

        $this->registerClientScript();
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $value = parent::renderDataCellContent($model, $key, $index);
        if (is_callable($this->editableOptions)) {
            $opts = call_user_func($this->editableOptions, $model, $key, $index);
            foreach ($opts as $prop => $v) {
                $this->options['data-' . $prop] = $v;
            }
        } elseif (is_array($this->editableOptions)) {
            foreach ($this->editableOptions as $prop => $v) {
                $this->options['data-' . $prop] = $v;
            }
        }

        $url = (array)$this->url;
        $this->options['data-url'] = Url::to($url);
        $this->options['data-pk'] = base64_encode(serialize($key));
        $this->options['data-name'] = $this->attribute;
        $this->options['data-type'] = $this->type;

        return Html::a($value, null, $this->options);
    }

    /**
     * Registers required script to the columns work
     */
    protected function registerClientScript()
    {
        $view = $this->grid->getView();
        $language = $this->language;

        switch ($this->type) {
            case 'address':
                EditableAddressAsset::register($view);
                break;
            case 'combodate':
                EditableComboDateAsset::register($view);
                break;
            case 'date':
                if ($language) {
                    EditableDatePickerAsset::register(
                        $view
                    )->js[] = 'vendor/js/locales/bootstrap-datetimepicker.' . $language . '.js';
                } else {
                    EditableDatePickerAsset::register($view);
                }
                break;
            case 'datetime':
                if ($language) {
                    EditableDateTimePickerAsset::register(
                        $view
                    )->js[] = 'vendor/js/locales/bootstrap-datetimepicker.' . $language . '.js';
                } else {
                    EditableDateTimePickerAsset::register($view);
                }
                break;
            case 'select2':
                EditableSelect2Asset::register($view);
                break;
            case 'wysihtml5':
                $language = $language ?: 'en-US';
                EditableWysiHtml5Asset::register(
                    $view
                )->js[] = 'vendor/locales/bootstrap-wysihtml5.' . $language . '.js';
                break;
            default:
                EditableBootstrapAsset::register($view);
        }

        EditableColumnAsset::register($view);
        $rel = $this->options['rel'];
        $selector = "a[rel=\"$rel\"]";
        $grid = "#{$this->grid->id}";
        $script = ";jQuery('$selector').editable();";
        $hash = hash('crc32', $script);
        $js = [];
        $js[] = $script;
        $js[] = "dosamigos.editableColumn.registerHandler('$grid', '$selector', '$hash');";
        $view->registerJs(implode("\n", $js));
    }
}
