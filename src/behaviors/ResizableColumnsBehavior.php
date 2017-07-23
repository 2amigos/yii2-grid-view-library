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

class ResizableColumnsBehavior extends Behavior implements RegistersClientScriptInterface
{
    /**
     * @var bool whether or not use a store. Defaults to true.
     */
    public $store = true;

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

        $options = $this->store === true ? '{store: window.store}' : '';

        $view->registerJs(";jQuery('#$id > table.dosamigos-grid-view-table').resizableColumns($options);");
    }
}
