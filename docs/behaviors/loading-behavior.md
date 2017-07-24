LoadingBehavior
===============

When using `Pjax` widget, displays processing visual feedback to the user by displaying a loading gif on the grid. The 
behavior currently supports three types of display: `bars`, `spinner`, and `reload`. Other types can be added upon 
request.

> **Note** Pjax is automatically discovered. This behavior only makes sense when used with pjax.

### Usage

Simply attach it to the GridView widget like this: 

```php 
use dosamigos\grid\GridView;

echo GridView::widget(
    [
        'behaviors' => [
            [
                'class' => '\dosamigos\grid\behaviors\LoadingBehavior',
                'type' => 'spinner'
            ]
        ],
        // ... other settings 
    ]
);
```


© [2amigos](http://www.2amigos.us/) 2013-2017
