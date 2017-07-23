EditableColumn
==============

To make use of this column, we need to register the [2amigos/yii2-editable-widget](https://github.com/2amigos/yii2-editable-widget) 
package. The column exposes the option to configure the widget on a per column basis. 

> **Note** So, for further information about the options you have to configure the Column please go to 
[the editable widget's repository](https://github.com/2amigos/yii2-editable-widget).


## Usage 

**Configure the editable action on a controller**

This action will handle the update calls from the widget:

```php 

// ... other config ...
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

```

**Configure the editable column on a grid**

```php 
// ... other columns above?
[
    'class' => \dosamigos\grid\EditableColumn::className(),
    'attribute' => 'name',
    'url' => ['editable'], // the route to the editable action!
    'type' => 'date',
    'editableOptions' => [
        'mode' => 'inline',
    ]
],
// ... 
```

 
© [2amigos](http://www.2amigos.us/) 2013-2017
