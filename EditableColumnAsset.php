<?php
/**
 *
 * ToggleColumnAsset.php
 *
 * Date: 03/04/14
 * Time: 23:19
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
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