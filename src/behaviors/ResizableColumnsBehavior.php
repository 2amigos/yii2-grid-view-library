<?php

/*
 * This file is part of the 2amigos/yii2-grid-view-library project.
 * (c) 2amigOS! <http://2amigos.us/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace dosamigos\grid\behaviors;

use dosamigos\grid\bundles\ResizableColumnsBundle;
use dosamigos\grid\contracts\RegistersClientScriptInterface;
use yii\base\Behavior;
use yii\grid\GridView;
use yii\helpers\Json;

class ResizableColumnsBehavior extends Behavior implements RegistersClientScriptInterface
{
    /**
     * @var array $clientOptions the options for the underlying resizable jquery plugin. Sets by default the store
     * option. These are the options available and its defaults (translated from its js code):
     *
     * ```
     * [
     *  'selector' => new JsExpression('function selector($table) {... see js code ...}'),
     *  'store' => new JsExpression('window.store'),
     *  'syncHandlers' => true,
     *  'resizeFromBody' => true,
     *  'maxWidth' => new JsExpression('null'),
     *  'minWidth' => 0.01
     * ]
     * ```
     *
     * It also has the options for you to configure the callback functions when triggering events. The events are:
     *
     * - start: When plugin starts resizing
     * - resize: When plugin is resizing
     * - stop: When plugin ends resizing
     *
     * To configure do:
     *
     * ```
     * [
     *  'start' => new JsExpression('function(){ console.log("resizing...");}')
     * ]
     */
    public $clientOptions = [];

    /**
     * @inheritdoc
     */
    public function registerClientScript()
    {
        /** @var GridView $owner */
        $owner = $this->owner;

        $id = $owner->getId();
        $view = $owner->getView();

        ResizableColumnsBundle::register($view);

        $options = !empty($this->clientOptions)
            ? Json::encode($this->clientOptions)
            : '';

        $view->registerJs(";jQuery('#$id > table.da-grid-view-table').resizableColumns($options);");
    }
}
