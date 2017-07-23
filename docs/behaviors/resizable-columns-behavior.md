ResizableColumnsBehavior
=======================

It handles the initialization of a jquery plugin to allow column resize. It implements the 
`dosamigos\grid\contracts\RegistersClientScriptInterface` so the grid knows it has to call the `registerClientScript()` 
method of the behavior. 

The jquery plugin is a modified version of [jQuery Resizable Columns](http://dobtco.github.io/jquery-resizable-columns/) 
so to be able to work with GridView tables. 

It has a `clientOptions` attribute that you can set to configure the options available on the plugin (check its source)

> **Note** This behavior has conflicts when used with [FloatHeaderBehavior](float-header-behavior.md).

### Usage

Simply attach it to the GridView widget like this: 

```php 
use dosamigos\grid\GridView;

echo GridView::widget(
    [
        'behaviors' => [
            [
                'class' => '\dosamigos\grid\behaviors\ResizableColumnsBehavior',
                'clientOptions' => [ ... plugin options ... ]
            ]
        ],
        // ... other settings 
    ]
);
```


© [2amigos](http://www.2amigos.us/) 2013-2017
