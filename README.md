GridView Extensions Library for Yii2
====================================

This library holds extensions specifically created to enhance Yii2's GridView Widget. We expect it to grow... :)


Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require "2amigos/yii2-grid-view-library" "*"
```
or add

```json
"2amigos/yii2-grid-view-library" : "*"
```

to the require section of your application's `composer.json` file.


Usage
-----

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
***Using EditableColumn and EditableAction***  
As you will soon realize, this column requires [2amigos/yii2-editable-widget](https://github.com/2amigos/yii2-editable-widget). So, for further information about the options you have to configure the Column please go to [the widget's repository](https://github.com/2amigos/yii2-editable-widget).

```
// on your controller 

public function actions()
{
    return [
        // ...
        'editable' => [
            'class' => EditableAction::className(),
            'modelClass' => Lang::className(),
            'forceCreate' => false
        ]
        // ...
    ];


// on your grid
// ... other columns above?
[
    'class' => \dosamigos\grid\EditableColumn::className(),
    'attribute' => 'name',
    'url' => ['editable'],
    'type' => 'date',
    'editableOptions' => [
        'mode' => 'inline',
    ]
],
// ... 
```


> [![2amigOS!](http://www.gravatar.com/avatar/55363394d72945ff7ed312556ec041e0.png)](http://www.2amigos.us)  
<i>Web development has never been so fun!</i>  
[www.2amigos.us](http://www.2amigos.us)