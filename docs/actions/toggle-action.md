ToggleAction Component
======================

***Using ToggleColumn and ToggleAction***

```
// on your controller

public function actions()
{
    return [
        // ...
        'toggle' => [
            'class' => ToggleAction::className(),
            'modelClass' => Lang::className(),
            'onValue' => Lang::STATUS_ACTIVE,
            'offValue' => Lang::STATUS_NOT_ACTIVE
        ],
        // ...
    ];
}


// on your grid
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
// ...
```

© [2amigos](http://www.2amigos.us/) 2013-2017
