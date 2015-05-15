<?php
/**
 * @link https://github.com/2amigos/yii2-grid-view-library
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace dosamigos\grid;

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
        'yii\web\YiiAsset',
        'dosamigos\assets\DosAmigosAsset',
    ];

}
