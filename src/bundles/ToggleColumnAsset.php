<?php

/*
 * This file is part of the 2amigos/yii2-grid-view-library project.
 * (c) 2amigOS! <http://2amigos.us/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace dosamigos\grid\bundles;

use yii\web\AssetBundle;

/**
 * ToggleColumnAsset
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\grid
 */
class ToggleColumnAsset extends AssetBundle
{
    public $sourcePath = '@vendor/2amigos/yii2-grid-view-library/assets/toggle';

    public $js = ['js/dosamigos-toggle.column.js'];

    public $depends = [
        'yii\web\JqueryAsset',
        'dosamigos\assets\DosAmigosAsset',
    ];
}
