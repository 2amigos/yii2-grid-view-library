<?php
/**
 * @link https://github.com/2amigos/yii2-grid-view-library
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace dosamigos\grid;

use yii\web\AssetBundle;

class EditableColumnAsset extends AssetBundle
{
    public $sourcePath = '@vendor/2amigos/yii2-grid-view-library/assets/editable';

    public $js = ['js/dosamigos-editable.column.js'];

    public $depends = [
        'yii\web\YiiAsset',
        'dosamigos\assets\DosAmigosAsset',
    ];

}
