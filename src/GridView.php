<?php

/*
 * This file is part of the 2amigos/yii2-grid-view-library project.
 * (c) 2amigOS! <http://2amigos.us/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace dosamigos\grid;

use dosamigos\grid\contracts\RegistersClientScriptInterface;
use dosamigos\grid\contracts\RunnableBehaviorInterface;
use yii\base\Behavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class GridView extends \yii\grid\GridView
{
    /**
     * @var Behavior[]|array the attached behaviors (behavior name => behavior).
     */
    private $gBehaviors = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        Html::addCssClass($this->options, 'da-grid-view');
        Html::addCssClass($this->tableOptions, 'da-grid-view-table');
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge($this->gBehaviors, parent::behaviors());
    }

    /**
     * Provide the option to be able to set behaviors on GridView configuration.
     *
     * @param array $behaviors
     */
    public function setBehaviors(array $behaviors = [])
    {
        $this->gBehaviors = $behaviors;
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
        $behaviors = $this->getBehaviors();

        if (is_array($behaviors)) {
            foreach ($behaviors as $behavior) {
                /** @var Object $behavior */
                if ($behavior->hasMethod($method)) {
                    return call_user_func([$behavior, $method]);
                }
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

        $this->runBehaviors();
    }

    /**
     * Runs behaviors, registering their scripts if necessary
     */
    protected function runBehaviors()
    {
        $behaviors = $this->getBehaviors();

        if (is_array($behaviors)) {
            foreach ($behaviors as $behavior) {
                if ($behavior instanceof RegistersClientScriptInterface) {
                    $behavior->registerClientScript();
                }
                if($behavior instanceof RunnableBehaviorInterface) {
                    $behavior->run();
                }
            }
        }
    }
}
