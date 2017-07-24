<?php

/*
 * This file is part of the 2amigos/yii2-grid-view-library project.
 * (c) 2amigOS! <http://2amigos.us/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace dosamigos\grid\behaviors;

use yii\base\Behavior;
use yii\bootstrap\ButtonGroup;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ToolbarBehavior extends Behavior
{
    /**
     * @var array $buttons the buttons that would be rendered within the toolbar. The buttons configuration are exactly the
     *            same as http://www.yiiframework.com/doc-2.0/yii-bootstrap-buttongroup.html to display them with one difference,
     *            multiple button groups can be displayed by providing a separator element:
     *
     * ```
     * 'buttons' => [
     *      ['label' => 'A'],
     *      ['label' => 'B', 'visible' => false],
     *      '-', // divider
     *      ['label' => 'C'], // this will belong to a different group
     * ]
     * ```
     * @see http://www.yiiframework.com/doc-2.0/yii-bootstrap-buttongroup.html#$buttons-detail
     */
    public $buttons = [];
    /**
     * @var boolean whether to HTML-encode the button labels of the button groups.
     */
    public $encodeLabels = true;
    /**
     * @var array $buttonGroupOptions the options to pass to the button groups. Currently are global. For example:
     *
     * ```
     * 'buttonGroupOptions' => ['class' => 'btn-group-lg']
     * ```
     */
    public $buttonGroupOptions = [];
    /**
     * @var array the options for the toolbar tag.
     */
    public $options = [];
    /**
     * @var array the options for the toolbar container.
     */
    public $containerOptions = [];
    /**
     * @var string toolbar alignment, defaults to right alignment.
     */
    public $alignRight = true;
    /**
     * @var array contains the grouped buttons
     */
    protected $groups = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initOptions();
        $this->initButtonGroups();
    }

    /**
     * Renders the toolbar.
     *
     * @return string
     */
    public function renderToolbar()
    {
        if (empty($this->groups)) {
            return '';
        }
        $content = [];
        foreach ($this->groups as $buttons) {
            $content[] = ButtonGroup::widget(
                ['buttons' => $buttons, 'encodeLabels' => $this->encodeLabels, 'options' => $this->buttonGroupOptions]
            );
        }
        $toolbar = Html::tag('div', implode("\n", $content), $this->options);

        $container = Html::tag('div', $toolbar, $this->containerOptions);

        if ($this->alignRight) {
            $container .= '<div class="clearfix" style="margin-top:5px"></div>';
        }

        return $container;
    }

    /**
     * Initializes toolbar options
     */
    protected function initOptions()
    {
        $this->options = ArrayHelper::merge($this->options, ['class' => 'btn-toolbar', 'role' => 'toolbar']);
        if ($this->alignRight === true) {
            Html::addCssClass($this->containerOptions, 'pull-right');
        }
    }

    /**
     * Parses the buttons to check for possible button group separations.
     */
    protected function initButtonGroups()
    {
        $group = [];
        foreach ($this->buttons as $button) {
            if (is_string($button) && $button == '-') {
                $this->groups[] = $group;
                $group = [];
                continue;
            }
            $group[] = $button;
        }
        if (!empty($group)) {
            $this->groups[] = $group;
        }
    }
}
