<?php

namespace dosamigos\grid\bundles;

use yii\web\AssetBundle;

class FloatTableHeadAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery.floatthead/dist';

    public $js = [
        'jquery.floatThead.js'
    ];
}
