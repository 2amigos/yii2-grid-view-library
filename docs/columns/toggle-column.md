ToggleColumn
============

Allows you to modify a two-states type value of a column on the fly. Two-states type values are, for example, yes/no, 
true/false, 1/0, and so on...

The Column works in conjunction with the [ToggleAction](../actions/toggle-action.md).

###Usage 

**Configuring the ToggleAction**

On your controller:

```php 
public function actions()
{
    return [
        // ...
        'toggle' => [
            'class' => ToggleAction::className(),
            'modelClass' => Lang::className(),
            'onValue' => Status::STATUS_ACTIVE,       // Status::STATUS_ACTIVE = 1
            'offValue' => Status::STATUS_NOT_ACTIVE   // Status::STATUS_NOT_ACTIVE = 0
        ],
        // ...
    ];
}
```

**Configuring the ToggleColumn**

```php
// ... other columns above?
[
    'class' => \dosamigos\grid\ToggleColumn::className(),
    'attribute' => 'status',
    'onValue' => Status::STATUS_ACTIVE,
    'onLabel' => 'Active',
    'offLabel' => 'Not active',
    'contentOptions' => ['class' => 'text-center'],
    'afterToggle' => 'function(r, data){if(r){console.log("done", data)};}',
    'filter' => [
        Status::STATUS_ACTIVE => 'Active',
        Status::STATUS_DELETED => 'Not active'
    ]
],
// ... other columns below?
```


© [2amigos](http://www.2amigos.us/) 2013-2017
