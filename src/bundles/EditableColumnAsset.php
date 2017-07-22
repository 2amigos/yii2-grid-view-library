<?php

/*
 * This file is part of the 2amigos/yii2-grid-view-library project.
 * (c) 2amigOS! <http://2amigos.us/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace dosamigos\grid\bundles;

use yii\web\AssetBundle;

class EditableColumnAsset extends AssetBundle
{
    public $sourcePath = '@vendor/2amigos/yii2-grid-view-library/assets/editable';

    public $js = [
        'js/dosamigos-editable.column.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'dosamigos\assets\DosAmigosAsset',
    ];
}
