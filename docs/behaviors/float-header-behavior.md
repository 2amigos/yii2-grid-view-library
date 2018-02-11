FloatHeaderBehavior
===================

It handles the initialization of a jquery plugin that creates floating headers. It implements the 
`dosamigos\grid\contracts\RegistersClientScriptInterface` so the grid knows it has to call the `registerClientScript()` 
method of the behavior. 

The jquery plugin is the great [jQuery.floatThead](http://mkoryak.github.io/floatThead/) plugin. 
so to be able to work with GridView tables. Check the [plugin options](http://mkoryak.github.io/floatThead/#options) for 
further information regarding the configuration options and events of the plugin.

> **Note** This behavior cannot be used with [ResizableColumnsBehavior](resizable-columns-behavior.md) as it 
> requires the headers to be on fixed width. 

### Usage

Simply attach it to the GridView widget like this: 

```php 
use dosamigos\grid\GridView;
use yii\web\JsExpression;

echo GridView::widget(
    [
        'behaviors' => [
            [
                'class' => '\dosamigos\grid\behaviors\FloatHeaderBehavior',
                'clientOptions' => [ // ... plugin options
                    'floatContainerClass' => 'white',
                    'top' => 50
                ],
                'clientEvents' => [
                    'floatThead' => new JsExpression("function(e, isFloated, $container){ console.log('...'); }");
                ]
            ]
        ],
        // ... other settings 
    ]
);
```


© [2amigos](http://www.2amigos.us/) 2013-2017
