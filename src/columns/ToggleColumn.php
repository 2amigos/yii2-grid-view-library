<?php

/*
 * This file is part of the 2amigos/yii2-grid-view-library project.
 * (c) 2amigOS! <http://2amigos.us/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace dosamigos\grid\columns;

use dosamigos\grid\bundles\ToggleColumnAsset;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * ToggleColumn Allows to modify the value of column on the fly. This type of column is good when you wish to modify
 * the value of a displayed model that has two states: yes-no, true-false, 1-0, etc...
 */
class ToggleColumn extends DataColumn
{
    /**
     * @var int the value to check against `On` state.
     */
    public $onValue = 1;
    /**
     * @var string the label for the toggle button. Defaults to "Check".
     *             Note that the label will not be HTML-encoded when rendering.
     */
    public $onLabel;
    /**
     * @var string the label for the toggle button. Defaults to "Uncheck".
     *             Note that the label will not be HTML-encoded when rendering.
     */
    public $offLabel;
    /**
     * @var string the label for the NULL value toggle button. Defaults to "Not Set".
     *             Note that the label will not be HTML-encoded when rendering.
     */
    public $emptyLabel;
    /**
     * @var string the glyph icon toggle button "checked" state.
     *             You may set this property to be false to render a text link instead.
     */
    public $onIcon = 'glyphicon glyphicon-ok-circle';
    /**
     * @var string the glyph icon toggle button "unchecked" state.
     *             You may set this property to be false to render a text link instead.
     */
    public $offIcon = 'glyphicon glyphicon-remove-circle';
    /**
     * @var string the glyph icon toggle button "empty" state (example for null value)
     */
    public $emptyIcon = 'glyphicon glyphicon-question-sign';
    /**
     * @var boolean display button with text or only icon with label tooltip
     */
    public $asText = false;
    /**
     * @var string Name of the action to call and toggle values. Defaults to `toggle`.
     * @see [[ToggleAction]] for an easy way to use with your controller
     */
    public $url = ['toggle'];
    /**
     * @var string a javascript function that will be invoked after the toggle ajax call.
     *
     * The function signature is `function(success, data)`.
     *
     * - success:  status of the ajax call, true if the ajax call was successful, false if the ajax call failed.
     * - data: the data returned by the server in case of a successful call or XHR object in case of error.
     *
     * Note that if success is true it does not mean that the delete was successful, it only means that the ajax call
     * was successful.
     *
     * Example:
     *
     * ```
     *  [
     *     'class'=> ToggleColumn::className(),
     *     'afterToggle'=>'function(success, data){ if (success) alert("Toggled successfully"); }',
     *  ],
     * ```
     */
    public $afterToggle;
    /**
     * @var string suffix substituted to a name class of the tag <a>
     */
    public $classSuffix;

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
        Html::addCssClass($this->headerOptions, 'toggle-column');
        Html::addCssClass($this->footerOptions, 'toggle-column');
        $this->format = 'raw';
        $this->onLabel = $this->onLabel ?: Yii::t('app', 'On');
        $this->offLabel = $this->offLabel ?: Yii::t('app', 'Off');
        $this->emptyLabel = $this->emptyLabel ?: Yii::t('app', 'Not set');
        $this->registerClientScript();
    }

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
        Html::addCssClass($options, 'toggle-column');

        return Html::tag('td', $this->renderDataCellContent($model, $key, $index), $options);
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $value = parent::renderDataCellContent($model, $key, $index);

        $options = ['class' => $this->attribute . '_toggle' . $this->classSuffix, 'data-pjax' => '0'];

        $url = $this->url;

        if (isset($url)) {
            if ($model instanceof Model) {
                $keys = $model->primaryKey();
                $key = $keys[0];
            }

            $url = (array)$this->url;
            $url['attribute'] = $this->attribute;
            $url['id'] = ($model instanceof Model)
                ? $model->getPrimaryKey()
                : $key;
            $url = Url::to($url);
        }

        if (!$this->asText) {
            $text = $value === null ? $this->emptyIcon : ($value === $this->onValue ? $this->onIcon : $this->offIcon);
            $text = Html::tag('span', '', ['class' => $text]);
            $options['title'] = $this->getToggleText($value);
            $options['rel'] = 'tooltip';
        } else {
            $text = $this->getToggleText($value);
        }

        return Html::a($text, $url, $options);
    }

    /**
     * @param $value
     *
     * @return string
     */
    protected function getToggleText($value)
    {
        return $value === null ? $this->emptyLabel : ($value === $this->onValue ? $this->onLabel : $this->offLabel);
    }

    /**
     * Registers the required scripts for the toggle column to work properly
     */
    protected function registerClientScript()
    {
        $view = $this->grid->getView();

        ToggleColumnAsset::register($view);
        $grid = $this->grid->id;
        $selector = "#$grid a.{$this->attribute}_toggle$this->classSuffix";
        $callback = $this->afterToggle ?: 'function(){}';

        $onText = $this->asText ? $this->onLabel : Html::tag('span', '', ['class' => $this->onIcon]);
        $offText = $this->asText ? $this->offLabel : Html::tag('span', '', ['class' => $this->offIcon]);

        $js = [];
        $js[] = "dosamigos.toggleColumn.onText='{$onText}'";
        $js[] = "dosamigos.toggleColumn.offText='{$offText}'";
        $js[] = "dosamigos.toggleColumn.onTitle='{$this->onLabel}'";
        $js[] = "dosamigos.toggleColumn.offTitle='{$this->offLabel}'";
        $js[] = "dosamigos.toggleColumn.registerHandler('$grid','$selector', $callback);";

        $view->registerJs(implode("\n", $js));
    }
}
