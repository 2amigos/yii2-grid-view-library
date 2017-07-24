<?php

/*
 * This file is part of the 2amigos/yii2-grid-view-library project.
 * (c) 2amigOS! <http://2amigos.us/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace dosamigos\grid\behaviors;

use dosamigos\grid\bundles\FloatTableHeadAsset;
use dosamigos\grid\contracts\RegistersClientScriptInterface;
use yii\base\Behavior;
use yii\grid\GridView;
use yii\helpers\Json;

/**
 * Notes: If you use this behavior, add this meta tag into your header to placate IE:
 * <meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />
 */
class FloatHeaderBehavior extends Behavior implements RegistersClientScriptInterface
{
    /**
     * @var array $clientOptions the options for the underlying floatThead jquery plugin. The default offset is set to
     *            `50` assuming a fixed bootstrap navbar on top.
     *
     * @see http://mkoryak.github.io/floatThead#options
     */
    public $clientOptions = [
        'top' => 50
    ];
    /**
     * @var array $clientEvents the events of the underlying floatThead jquery plugin. For example, to set the
     *            floatThead event:
     *
     * ```
     * 'floatThead' => new JsExpression('function(e, isFloated, $floatContainer) {
     *      if(isFloated) {
     *          $floatContainer.addClass("floated");
     *      }
     *
     * }')
     * ```
     *
     * @see http://mkoryak.github.io/floatThead/#options
     */
    public $clientEvents = [];

    /**
     * @inheritdoc
     */
    public function registerClientScript()
    {
        /** @var GridView $owner */
        $owner = $this->owner;

        $view = $owner->getView();

        FloatTableHeadAsset::register($view);

        $options = !empty($this->clientOptions)
            ? Json::encode($this->clientOptions)
            : '';

        $js = [];
        $id = $owner->getId();
        $js[] = ";jQuery('#$id > table.da-grid-view-table').floatThead($options);";
        if (!empty($this->clientEvents)) {
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = "jQuery('#$id > table.da-grid-view-table').on('$event', $handler);";
            }
        }
        $view->registerJs(implode("\n", $js));
    }
}
