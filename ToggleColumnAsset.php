<?php
/**
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace common\extensions\grid;

use yii\web\AssetBundle;

/**
 * ToggleColumnAsset
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package common\extensions\grid
 */
class ToggleColumnAsset extends AssetBundle
{
    public $sourcePath = '@2amigos/yii2-grid-view-library/assets/toggle';

    public $js = ['js/dosamigos-toggle.column.js'];

    public $depends = [
        'yii\web\YiiAsset',
    ];

} 