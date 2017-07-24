<?php

/*
 * This file is part of the 2amigos/yii2-grid-view-library project.
 * (c) 2amigOS! <http://2amigos.us/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace dosamigos\grid\behaviors;

use dosamigos\grid\bundles\LoadingAsset;
use dosamigos\grid\contracts\RegistersClientScriptInterface;
use yii\base\Behavior;
use yii\grid\GridView;

class LoadingBehavior extends Behavior implements RegistersClientScriptInterface
{
    /**
     * @var string the type of loading gif to display. Currently supports 'bars', 'reload' and 'spinner'. Defaults to
     * 'spinner'.
     */
    public $type = 'spinner';

    /**
     * @inheritdoc
     */
    public function registerClientScript()
    {
        /** @var GridView $owner */
        $owner = $this->owner;

        $view = $owner->getView();

        LoadingAsset::register($view);

        $id = $owner->getId();
        $type = $this->type;
        $hash = hash('crc32', $id.$type);

        $view->registerJs(";dosamigos.loadingGrid.registerHandler('#$id', '$type', '$hash');");
    }
}
