ToolbarBehavior
===============

It provides the ability to render a toolbar. The toolbar works in conjunction with the `yii\bootstrap\ButtonGroup` 
widget so is very easy to create your very own toolbar. 

You can use `yii\bootstrap\Button` widget configurations, as widget to render its resulting string or your very own 
widget buttons as long as it returns a string to render. 

See [Yii's ButtonGroup](http://www.yiiframework.com/doc-2.0/yii-bootstrap-buttongroup.html#$buttons-detail) for further 
information.
 
### Usage 

The usage is quite simple, you attach the behavior to a `dosamigos\grid\GridView` widget and then add the template 
token `{toolbar}` to your layout's template. 

For further information regarding the replacement tokens see the [how to create behaviors guide](../guides/how-to-create-behaviors.md)

> **Note** In order to keep the behavior as flexible as its the grid, it will only render the buttons but not its 
> functionality (scripts). We believe that is much better for us developers to have the freedom to create our very own 
> methods. If they are too repetitive, we recommend you to create your own widget (ie EditButton widget) and then use 
> it to display your reusable button on the toolbar.

**Displaying a simple toolbar** 

```php 
use dosamigos\grid\GridView;
use dosamigos\grid\behaviors\ToolbarBehavior;

$provider = ...;

echo GridView::widget(
    [
        'behaviors' => [
            [
                'class' => 'ToolbarBehavior',
                'encodeLabels' => false, // like this we will be able to display HTML on our buttons
                'buttons' => [
                    ['label' => '<i class="glyphicon glyphicon-plus"></i>', 'options' => ['class' => 'btn-default']],
                    ['label' => 'B', 'options' => ['class' => 'btn-warning']],
                ]
            ]

        ],
        'dataProvider' => $provider,
        'layout' => "{toolbar}<div class="clearfix"></div>\n{summary}\n{items}\n{pager}"
    ]
);

```

**Displaying a simple toolbar with left alignment**

```php 
use dosamigos\grid\GridView;
use dosamigos\grid\behaviors\ToolbarBehavior;

$provider = ...;

echo GridView::widget(
 [
     'behaviors' => [
         [
             'class' => 'ToolbarBehavior',
             'containerOptions' => [], // add your own classes, styles to the wrapper!
             'buttons' => [
                 ['label' => 'A', 'options' => ['class' => 'btn-default']],
                 ['label' => 'B', 'options' => ['class' => 'btn-warning']],
             ]
         ]

     ],
     'dataProvider' => $provider,
     'layout' => "{toolbar}\n{summary}\n{items}\n{pager}"
 ]
);
```

**Display a toolbar with multiple button groups** 

In order to display multiple toolbars simply add a string separator `-` and the buttons will be splitted in different 
groups: 

```php 
use dosamigos\grid\GridView;
use dosamigos\grid\behaviors\ToolbarBehavior;

$provider = ...;

echo GridView::widget(
    [
        'behaviors' => [
            [
                'class' => 'ToolbarBehavior',
                'encodeLabels' => false, 
                'buttons' => [
                    ['label' => '<i class="glyphicon glyphicon-plus"></i>', 'options' => ['class' => 'btn-default']],
                    ['label' => 'B', 'options' => ['class' => 'btn-warning']],
                    '-',
                    ['label' => 'C', 'options' => ['class' => 'btn-primary']],
                    ['label' => 'D', 'options' => ['class' => 'btn-primary']],
                    '-',
                    ['label' => 'E', 'options' => ['class' => 'btn-danger']],
                ]
            ]

        ],
        'dataProvider' => $provider,
        'layout' => "{toolbar}\n{summary}\n{items}\n{pager}"
    ]
);

```

**Official buttons**

We have created a set of official buttons so you can get a grasp on how to create buttons for this toolbar. The buttons 
added are: 

- `ReloadButton`: Reloads the grid. Automatically checks if pjax is being used on the grid and will ajax reload it.


***Buttons Usage***

```
use yii\widgets\Pjax;
use dosamigos\grid\GridView;
use dosamigos\grid\behaviors\ResizableColumnsBehavior;
use dosamigos\grid\behaviors\LoadingBehavior;
use dosamigos\grid\behaviors\ToolbarBehavior;
use dosamigos\grid\buttons\ReloadButton;


Pjax::begin(['id' => 'test-pjax']);
echo GridView::widget(
    [
        'behaviors' => [
            'ResizableColumnsBehavior',
            'LoadingBehavior',
            [
                'class' => 'ToolbarBehavior',
                 // Able to display HTML on our buttons
                'encodeLabels' => false,
                'buttons' => [
                    ReloadButton::widget(['options' => ['class' => 'btn-success']]),
                ]
            ]

        ],
        // ...
    ]
```


© [2amigos](http://www.2amigos.us/) 2013-2017
