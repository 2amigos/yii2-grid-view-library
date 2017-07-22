<?php

/*
 * This file is part of the 2amigos/yii2-grid-view-library project.
 * (c) 2amigOS! <http://2amigos.us/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace dosamigos\grid\behaviors;

use dosamigos\grid\contracts\RegistersClientScriptInterface;
use yii\base\Behavior;

class ResizableColumnsBehavior extends Behavior implements RegistersClientScriptInterface
{
    /**
     * @var bool whether or not use a store. Defaults to true.
     */
    public $store = true;

    /**
     *
     */
    public function registerClientScript()
    {
    }
}
