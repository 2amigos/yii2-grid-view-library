<?php

/*
 * This file is part of the 2amigos/yii2-grid-view-library project.
 * (c) 2amigOS! <http://2amigos.us/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace dosamigos\grid;

use dosamigos\grid\contracts\RegistersClientScriptInterface;
use yii\base\Behavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class GridView extends \yii\grid\GridView
{
    /**
     * @var Behavior[]|array the attached behaviors (behavior name => behavior).
     */
    private $_behaviors = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        Html::addCssClass($this->tableOptions, 'dosamigos-grid-view-table');
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge($this->_behaviors, parent::behaviors());
    }

    /**
     * Provide the option to be able to set behaviors on GridView configuration.
     *
     * @param array $behaviors
     */
    public function setBehaviors(array $behaviors = [])
    {
        $this->_behaviors = $behaviors;
    }

    /**
     * Enhanced version of the render section to be able to work with behaviors that work directly with the
     * template.
     *
     * @param string $name
     *
     * @return bool|mixed|string
     */
    public function renderSection($name)
    {
        $method = 'render' . ucfirst(str_replace(['{', '}'], '', $name)); // methods are prefixed with 'render'!

        if ($this->hasMethod($method)) {
            return call_user_func([$this, $method]);
        }

        foreach ($this->getBehaviors() as $behavior) {
            /** @var Object $behavior */
            if ($behavior->hasMethod($method)) {
                return call_user_func($method, $behavior);
            }
        }

        return parent::renderSection($name);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        parent::run();

        $this->registerClientScript();
    }

    /**
     * Registers required client scripts of behaviors
     */
    protected function registerClientScript()
    {
        foreach ($this->getBehaviors() as $behavior) {
            if ($behavior instanceof RegistersClientScriptInterface) {
                $this->registerClientScript();
            }
        }
    }
}