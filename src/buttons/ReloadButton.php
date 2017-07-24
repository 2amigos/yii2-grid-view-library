<?php

/*
 * This file is part of the 2amigos/yii2-grid-view-library project.
 * (c) 2amigOS! <http://2amigos.us/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace dosamigos\grid\buttons;

use dosamigos\grid\bundles\ToolbarButtonAsset;
use dosamigos\grid\contracts\RegistersClientScriptInterface;
use yii\bootstrap\Button;
use yii\web\JsExpression;

class ReloadButton extends Button implements RegistersClientScriptInterface
{
    /**
     * @var string the button label
     */
    public $label = '<i class="glyphicon glyphicon-refresh"></i>';
    /**
     * @var boolean whether the label should be HTML-encoded.
     */
    public $encodeLabel = false;

    /**
     * Renders the widget.
     */
    public function run()
    {
        $this->registerClientScript();

        return parent::run();
    }

    /**
     * @inheritdoc
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        ToolbarButtonAsset::register($view);

        $this->clientEvents['click.reloadButton'] = new JsExpression('dosamigos.toolbarButtons.reload');
    }
}
